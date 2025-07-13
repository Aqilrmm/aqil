# app/api.py
from fastapi import FastAPI, Request
from pydantic import BaseModel
from ai.assistant import AIAssistant
from ai.characters.character_config import get_ai_characters

app = FastAPI()

# Cache assistant per chat_id agar state bisa dipertahankan sementara (opsional)
ASSISTANT_CACHE = {}

class ChatRequest(BaseModel):
    chat_id: str
    character: str
    message: str
    myname: str
    debug: bool = True

@app.post("/chat")
def chat_endpoint(req: ChatRequest):
    print(req.character)
    char = req.character  # Don't lowercase - keep original case for UUIDs
      
    # Gunakan cache assistant per user (opsional)
    if req.chat_id not in ASSISTANT_CACHE:
        ASSISTANT_CACHE[req.chat_id] = AIAssistant(char, debug=req.debug, chat_id=req.chat_id)
    
    assistant = ASSISTANT_CACHE[req.chat_id]
    result = assistant.get_response(req.message)

    return {
        "response": result["response"],
        "tokens": result["tokens"]
    }