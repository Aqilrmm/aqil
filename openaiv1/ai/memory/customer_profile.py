#customer_profile
import os
import json

PROFILE_DIR = "user_profile"
os.makedirs(PROFILE_DIR, exist_ok=True)

def _get_profile_path(chat_id):
    return os.path.join(PROFILE_DIR, f"{chat_id}.json")

def load_profile(chat_id):
    path = _get_profile_path(chat_id)
    if os.path.exists(path):
        with open(path, "r") as f:
            return json.load(f)
    return {}

def save_profile(chat_id, data):
    path = _get_profile_path(chat_id)
    with open(path, "w") as f:
        json.dump(data, f, indent=2)

def update_profile(chat_id, key, value):
    profile = load_profile(chat_id)
    profile[key] = value
    save_profile(chat_id, profile)

def get_profile_summary(chat_id):
    profile = load_profile(chat_id)
    if not profile:
        return ""
    return ". ".join([f"{k.capitalize()}: {v}" for k, v in profile.items()])
