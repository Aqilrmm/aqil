from .assistant import AIAssistant
from .characters.character_config import AI_CHARACTERS

def main():
    char = input("Pilih karakter (poet/teacher/philosopher): ").strip().lower()
    if char not in AI_CHARACTERS:
        char = "teacher"

    chat_id = input("Masukkan chat ID (misal: aqil123): ").strip()
    if not chat_id:
        chat_id = "default"

    debug = input("Aktifkan debug mode? (y/n): ").lower() == 'y'
    assistant = AIAssistant(char, debug=debug, chat_id=chat_id)

    print(f"\nBerbicara dengan {AI_CHARACTERS[char]['name']}.\nKetik 'quit' untuk keluar.")
    while True:
        user_input = input("\nAnda: ").strip()
        if user_input.lower() == "quit":
            break
        print(f"\nAssistant: {assistant.get_response(user_input)}")

if __name__ == "__main__":
    main()
