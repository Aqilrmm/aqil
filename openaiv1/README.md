# AI Assistant API

Sistem AI Assistant API berbasis FastAPI yang mendukung beragam karakter AI dengan manajemen percakapan dan fungsi khusus.

## Struktur Proyek

```
├── app/
│   ├── api.py                  # FastAPI endpoint
├── ai/
│   ├── assistant.py            # Core class untuk AI Assistant
│   ├── characters/
│   │   ├── character_config.py # Konfigurasi karakter AI
│   ├── functions/              # Fungsi khusus yang dapat dipanggil AI
│   ├── memory/
│   │   ├── conversation_memory.py # Pengelolaan memori percakapan
│   │   ├── customer_profile.py    # Profil pengguna
│   ├── utils/
│       ├── debug_logger.py     # Utilitas untuk debugging
```

## Fitur

- 🤖 **Multiple AI Characters**: Mendukung berbagai karakter AI dengan kepribadian yang berbeda
- 💬 **Persistent Conversation Memory**: Menyimpan riwayat percakapan per pengguna
- 🛠️ **Function Calling**: AI dapat memanggil fungsi khusus untuk menyelesaikan tugas
- 👤 **User Profiling**: Menggunakan informasi pengguna untuk personalisasi respons
- 🐞 **Debug Mode**: Opsi untuk melihat log detail untuk troubleshooting

## Prasyarat

1. Python 3.8+
2. OpenAI API key
3. Package yang dibutuhkan (install dengan `pip install -r requirements.txt`):
   - fastapi
   - uvicorn
   - pydantic
   - openai
   - python-dotenv

## Instalasi

1. Clone repositori ini
2. Buat file `.env` di root direktori dengan isi:
   ```
   OPENAI_API_KEY=your_openai_api_key_here
   ```
3. Install dependencies:
   ```bash
   pip install -r requirements.txt
   ```

## Menjalankan API

```bash
uvicorn app.api:app --reload
```

Server akan berjalan di `http://localhost:8000`

## Penggunaan API

### Endpoint: `/chat`

**Method**: POST

**Request Body**:

```json
{
  "chat_id": "unique_user_identifier",
  "character": "teacher",
  "message": "Pesan dari pengguna",
  "debug": false
}
```

**Parameter**:
- `chat_id` (string, required): Pengidentifikasi unik untuk pengguna/percakapan
- `character` (string, optional): Karakter AI yang digunakan - default: "teacher"
- `message` (string, required): Pesan dari pengguna
- `debug` (boolean, optional): Tampilkan log debug - default: false

**Response**:

```json
{
  "response": "Respons dari AI Assistant"
}
```

### Contoh Penggunaan

**cURL**:
```bash
curl -X POST http://localhost:8000/chat \
  -H "Content-Type: application/json" \
  -d '{"chat_id": "user123", "character": "teacher", "message": "Apa itu energi terbarukan?", "debug": false}'
```

**Python**:
```python
import requests

response = requests.post(
    "http://localhost:8000/chat",
    json={
        "chat_id": "user123",
        "character": "teacher",
        "message": "Apa itu energi terbarukan?",
        "debug": False
    }
)

print(response.json())
```

## Karakter AI

Sistem mendukung beberapa karakter AI yang didefinisikan dalam `ai/characters/character_config.py`. Default-nya adalah "teacher".

Untuk mengubah atau menambahkan karakter, edit file tersebut dengan format:

```python
AI_CHARACTERS = {
    "teacher": {
        "name": "Teacher",
        "system_prompt": "Kamu adalah asisten pengajar yang membantu..."
    },
    "doctor": {
        "name": "Doctor",
        "system_prompt": "Kamu adalah asisten medis yang membantu..."
    },
    # Tambahkan karakter lain
}
```

## Sistem Memori Percakapan

Sistem menyimpan riwayat percakapan dalam file JSON di folder `chat_memory/`. Setiap percakapan diidentifikasi dengan `chat_id` unik.

File memori menggunakan format:
```json
[
  {"role": "user", "content": "Pesan pengguna"},
  {"role": "assistant", "content": "Respons asisten"},
  {"role": "function", "name": "nama_fungsi", "content": "Hasil fungsi"}
]
```

## Function Calling

AI Assistant dapat memanggil fungsi khusus yang didefinisikan dalam sistem. Ada dua jenis fungsi:
- **Normal Functions**: Mengembalikan hasil untuk diproses lebih lanjut oleh AI
- **Silent Functions**: Menyimpan data tanpa mengubah alur percakapan (seperti menyimpan preferensi)

Untuk menambahkan fungsi baru, tambahkan ke `AVAILABLE_FUNCTIONS` dengan spesifikasi OpenAI function calling.

## Profil Pengguna

Sistem mendukung profil pengguna untuk personalisasi respons melalui `ai/memory/customer_profile.py`. Profil pengguna ditambahkan ke system prompt untuk memberikan konteks tambahan ke AI.

## Mode Debug

Aktifkan mode debug dengan mengatur parameter `debug: true` untuk melihat:
- Messages yang dikirim ke API
- Respons mentah dari API
- Hasil fungsi jika dipanggil

## Pengembangan

### Menambahkan Fungsi Baru

1. Buat fungsi di modul yang sesuai
2. Daftarkan di `ai/functions/__init__.py` dengan spesifikasi OpenAI function calling
3. Tentukan apakah fungsi "silent" atau tidak

### Menambahkan Karakter AI Baru

Tambahkan entri baru ke `AI_CHARACTERS` di `ai/characters/character_config.py` dengan format:
```python
"karakter_baru": {
    "name": "Nama Tampilan",
    "system_prompt": "Instruksi sistem untuk karakter AI"
}
```

## Troubleshooting

1. **Error OpenAI API**: Periksa API key di `.env`
2. **Memori Tidak Tersimpan**: Periksa izin tulis pada folder `chat_memory/`
3. **Karakter Tidak Tersedia**: Pastikan karakter didefinisikan di `character_config.py`

## Catatan Penting

- Sistem menggunakan model "gpt-4o-mini" dari OpenAI
- Setiap pesan yang dikirim ke API akan ditagih sesuai harga OpenAI
- Sistem menyimpan memori maksimal 6 pesan terakhir untuk performa optimal