#conversation.py
import os
import json

class ConversationMemory:
    def __init__(self, chat_id, max_messages=6, folder="chat_memory"):
        self.chat_id = chat_id
        self.max_messages = max_messages
        self.folder = folder
        self.filepath = os.path.join(self.folder, f"{chat_id}.json")
        os.makedirs(self.folder, exist_ok=True)
        self.messages = self._load()

    def add(self, role, content, name=None):
        message = {"role": role, "content": content}
        if role == "function" and name:
            message["name"] = name
        self.messages.append(message)
        self._save()

    def get(self):
        # Ambil hanya 6 pesan terakhir (short-term memory)
        return self.messages[-self.max_messages:]

    def _save(self):
        with open(self.filepath, "w") as f:
            json.dump(self.messages, f, indent=2)

    def _load(self):
        if os.path.exists(self.filepath):
            with open(self.filepath, "r") as f:
                return json.load(f)
        return []
