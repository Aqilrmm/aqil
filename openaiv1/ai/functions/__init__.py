from .update_user_profile import update_user_profile
from .sentiment import analyze_sentiment
from .count_words import count_words

AVAILABLE_FUNCTIONS = {
    "update_user_profile": {
        "func": update_user_profile,
        "silent": True,
        "spec": {
            "name": "update_user_profile",
            "description": "Store relevant user profile information. Call this function when user shares personal data such as name, interests, favorite drinks, routines, or location. Use appropriate keys such as 'name', 'hobby', 'favorite_drink', etc.",
            "parameters": {
                "type": "object",
                "properties": {
                    "key": {"type": "string"},
                    "value": {"type": "string"},
                    "chat_id": {"type": "string"}
                },
                "required": ["key", "value"]
            }
        }
    },
    "analyze_sentiment": {
        "func": analyze_sentiment,
        "spec": {
            "name": "analyze_sentiment",
            "description": "Analyze sentiment of text",
            "parameters": {
                "type": "object",
                "properties": {
                    "text": {"type": "string"}
                },
                "required": ["text"]
            }
        }
    },
    "count_words": {
        "func": count_words,
        "spec": {
            "name": "count_words",
            "description": "Count words in text",
            "parameters": {
                "type": "object",
                "properties": {
                    "text": {"type": "string"}
                },
                "required": ["text"]
            }
        }
    }
}
