from ..memory.customer_profile import update_profile

def update_user_profile(key: str, value: str, chat_id: str):
    """
    Fungsi untuk menyimpan informasi pengguna ke dalam profil jangka panjang (LTM).
    """
    update_profile(chat_id, key, value)
    return f"Profil diperbarui: {key} = {value}"
