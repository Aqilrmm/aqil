#assistant.py
import os
import json
from openai import OpenAI
from dotenv import load_dotenv

from .characters.character_config import get_ai_characters
from .functions import AVAILABLE_FUNCTIONS
from .memory.conversation_memory import ConversationMemory
from .memory.customer_profile import get_profile_summary
from .utils.debug_logger import debug_log

load_dotenv()

class AIAssistant:
    def __init__(self, character, debug=False, chat_id="default"):
        self.client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))
        self.character = get_ai_characters(character)
        self.memory = ConversationMemory(chat_id)
        self.chat_id = chat_id
        self.debug = debug

    def get_response(self, user_input):
        self.memory.add("user", user_input)

        profile_summary = get_profile_summary(self.chat_id)
        profile_prompt = f"Informasi pengguna: {profile_summary}" if profile_summary else ""

        messages = [{"role": "system", "content": f"{self.character['system_prompt']}\n{profile_prompt}"}]
        messages += self.memory.get()

        debug_log(self.debug, f"Messages: {messages}")

        api_response = self.client.chat.completions.create(
            model="gpt-4o-mini",
            messages=messages,
            functions=[f["spec"] for f in AVAILABLE_FUNCTIONS.values()],
            function_call="auto",
            temperature=0.7
        )
        response = api_response.choices[0].message
        usage = api_response.usage
        debug_log(self.debug, f"API Response: {response}")
        debug_log(self.debug, f"Token Usage: {usage}")
        if response.function_call:
            fn_name = response.function_call.name
            fn_args = json.loads(response.function_call.arguments)
            func_obj = AVAILABLE_FUNCTIONS.get(fn_name)

            if func_obj:
                func = func_obj["func"]
                is_silent = func_obj.get("silent", False)

                if "chat_id" not in fn_args:
                    fn_args["chat_id"] = self.chat_id

                result = func(**fn_args)
                self.memory.add("function", str(result), name=fn_name)
                debug_log(self.debug, f"Function result: {result}")

                if is_silent:
                    # Fungsi penyimpanan — langsung lanjutkan respons user
                    messages = [{"role": "system", "content": f"{self.character['system_prompt']}\n{profile_prompt}"}]
                    messages += self.memory.get()

                    response = self.client.chat.completions.create(
                        model="gpt-4o-mini",
                        messages=messages,
                        temperature=0.7
                    ).choices[0].message

                    self.memory.add("assistant", response.content)
                    return response.content
                else:
                    # Fungsi normal, kirim hasil function ke model
                    followup = self.client.chat.completions.create(
                        model="gpt-4o-mini",
                        messages=messages + [{
                            "role": "function",
                            "name": fn_name,
                            "content": str(result)
                        }],
                        temperature=0.7
                    ).choices[0].message

                    self.memory.add("assistant", followup.content)
                    return followup.content

        self.memory.add("assistant", response.content)
        return {
            "response": response.content,
            "tokens": {
                "prompt": usage.prompt_tokens,
                "completion": usage.completion_tokens,
                "total": usage.total_tokens
            }
        }
