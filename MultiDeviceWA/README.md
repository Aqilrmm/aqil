# 📱 WhatsApp Multi-Device API

API WhatsApp Multi-Device yang powerful menggunakan whatsapp-web.js dengan dukungan Socket.io untuk real-time communication. Aplikasi ini memungkinkan Anda mengelola multiple device WhatsApp secara bersamaan melalui REST API dan WebSocket.

## ✨ Fitur Utama

- 🔄 **Multi-Device Support**: Kelola hingga 10 device WhatsApp secara bersamaan
- 🌐 **REST API**: API endpoint yang lengkap untuk semua operasi
- ⚡ **Real-time WebSocket**: Update status dan pesan secara real-time menggunakan Socket.io
- 📱 **Dashboard Web**: Interface web yang responsif untuk monitoring dan kontrol
- 🔐 **QR Code Management**: Generate dan tampilkan QR code untuk pairing device
- 💬 **Message Operations**: Kirim pesan, balas pesan, dan terima pesan masuk
- 🔒 **Rate Limiting**: Proteksi dari spam dengan rate limiting
- 📊 **Monitoring**: Monitor status device dan statistik penggunaan
- 🔧 **Configuration**: Konfigurasi yang fleksibel melalui environment variables
- 📦 **Docker Support**: Mudah deploy menggunakan Docker
- 🚀 **PM2 Ready**: Konfigurasi siap untuk production dengan PM2

## 📋 Persyaratan Sistem

- **Node.js**: v16.0.0 atau lebih tinggi
- **NPM**: v8.0.0 atau lebih tinggi
- **RAM**: Minimal 1GB (disarankan 2GB+)
- **Storage**: Minimal 1GB free space untuk sessions dan QR codes
- **OS**: Linux, macOS, atau Windows

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/whatsapp-multidevice-api.git
cd whatsapp-multidevice-api
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Setup Environment Variables

```bash
cp .env.example .env
```

Edit file `.env` sesuai kebutuhan Anda:

```env
# Server Configuration
PORT=3000
BASE_URL=http://localhost:3000
NODE_ENV=development

# WhatsApp Configuration
SESSION_TIMEOUT=1800000
MAX_DEVICES=10
QR_EXPIRY_TIME=300000

# Rate Limiting
RATE_LIMIT_WINDOW=60000
RATE_LIMIT_MAX=100
MESSAGE_RATE_LIMIT_WINDOW=60000
MESSAGE_RATE_LIMIT_MAX=30
```

### 4. Jalankan Aplikasi

#### Development Mode
```bash
npm run dev
```

#### Production Mode
```bash
npm start
```

#### Using PM2
```bash
npm run pm2:start
```

### 5. Akses Dashboard

Buka browser dan kunjungi: `http://localhost:3000`

## 🐳 Docker Installation

### 1. Build dan Run dengan Docker Compose

```bash
docker-compose up -d
```

### 2. Build Manual

```bash
docker build -t whatsapp-api .
docker run -p 3000:3000 -v $(pwd)/sessions:/usr/src/app/sessions -v $(pwd)/qr-codes:/usr/src/app/qr-codes whatsapp-api
```

## 📖 API Documentation

### Base URL
```
http://localhost:3000/api
```

### Authentication
Jika `API_KEY` diset di environment variables, tambahkan header:
```
Authorization: Bearer YOUR_API_KEY
```

### Endpoints

#### 1. Health Check
```http
GET /api/health
```

**Response:**
```json
{
  "success": true,
  "status": "healthy",
  "timestamp": "2024-01-15T10:30:00.000Z",
  "stats": {
    "totalDevices": 2,
    "readyDevices": 1,
    "uptime": 3600
  }
}
```

#### 2. Device Management

##### Buat Device Baru
```http
POST /api/devices
Content-Type: application/json

{
  "deviceId": "device1"
}
```

##### Get Semua Device
```http
GET /api/devices
```

**Response:**
```json
{
  "success": true,
  "data": {
    "devices": [
      {
        "deviceId": "device1",
        "status": "ready",
        "timestamp": "2024-01-15T10:30:00.000Z",
        "hasQR": false
      }
    ],
    "total": 1,
    "ready": 1
  }
}
```

##### Get Device Tertentu
```http
GET /api/devices/{deviceId}
```

##### Get QR Code
```http
GET /api/devices/{deviceId}/qr
```

**Response:**
```json
{
  "success": true,
  "data": {
    "deviceId": "device1",
    "qrImageUrl": "http://localhost:3000/qr/device1.png",
    "qrCode": "1@abc123...",
    "timestamp": "2024-01-15T10:30:00.000Z"
  }
}
```

##### Hapus Device
```http
DELETE /api/devices/{deviceId}
```

#### 3. Messaging

##### Kirim Pesan
```http
POST /api/send
Content-Type: application/json

{
  "deviceId": "device1",
  "to": "6281234567890@c.us",
  "message": "Hello from WhatsApp API!"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "messageId": "msg123",
    "deviceId": "device1",
    "to": "6281234567890@c.us",
    "message": "Hello from WhatsApp API!",
    "timestamp": "2024-01-15T10:30:00.000Z"
  }
}
```

##### Balas Pesan
```http
POST /api/reply
Content-Type: application/json

{
  "deviceId": "device1",
  "messageId": "msg123",
  "reply": "Thank you for your message!"
}
```

#### 4. Webhook

##### Set Webhook URL
```http
POST /api/webhook
Content-Type: application/json

{
  "url": "https://your-webhook-url.com/webhook"
}
```

##### Get Webhook URL
```http
GET /api/webhook
```

## 🌐 WebSocket Events

### Client ke Server

```javascript
// Connect ke WebSocket
const socket = io('http://localhost:3000');

// Buat device baru
socket.emit('create_device', { deviceId: 'device1' });

// Kirim pesan
socket.emit('send_message', {
  deviceId: 'device1',
  to: '6281234567890@c.us',
  message: 'Hello World!'
});

// Hapus device
socket.emit('remove_device', { deviceId: 'device1' });

// Get status devices
socket.emit('get_devices_status');
```

### Server ke Client

```javascript
// Pesan selamat datang
socket.on('welcome', (data) => {
  console.log('Connected:', data);
});

// QR Code generated
socket.on('qr_code', (data) => {
  console.log('QR Code:', data.qrImageUrl);
});

// Status device berubah
socket.on('device_status', (data) => {
  console.log('Device Status:', data);
});

// Pesan masuk
socket.on('message_received', (data) => {
  console.log('New Message:', data);
});

// Error
socket.on('error', (error) => {
  console.error('Error:', error);
});
```

## 📱 Cara Pairing Device

1. **Buat Device Baru**
   ```bash
   curl -X POST http://localhost:3000/api/devices \
     -H "Content-Type: application/json" \
     -d '{"deviceId": "phone1"}'
   ```

2. **Dapatkan QR Code**
   ```bash
   curl http://localhost:3000/api/devices/phone1/qr
   ```

3. **Scan QR Code**
   - Buka WhatsApp di ponsel Anda
   - Pilih **Linked Devices** atau **WhatsApp Web**
   - Scan QR code yang muncul

4. **Tunggu Status Ready**
   - Monitor dashboard atau WebSocket events
   - Status akan berubah dari `qr_ready` ke `authenticated` lalu `ready`

## 🔧 Konfigurasi

### Environment Variables

| Variable | Default | Deskripsi |
|----------|---------|-----------|
| `PORT` | 3000 | Port server |
| `MAX_DEVICES` | 10 | Maksimal device yang dapat dikelola |
| `SESSION_TIMEOUT` | 1800000 | Timeout session (ms) |
| `QR_EXPIRY_TIME` | 300000 | Waktu expire QR code (ms) |
| `RATE_LIMIT_MAX` | 100 | Maksimal request per menit |
| `MESSAGE_RATE_LIMIT_MAX` | 30 | Maksimal pesan per menit |
| `API_KEY` | null | API key untuk authentication |

### Rate Limiting

- **API General**: 100 requests per menit
- **Send Message**: 30 pesan per menit
- Dapat dikonfigurasi melalui environment variables

## 📊 Monitoring & Logging

### Dashboard Web
- Real-time monitoring semua device
- Statistik penggunaan
- Management device melalui UI
- Live message monitoring

### Logs
```bash
# PM2 logs
npm run pm2:logs

# Manual logs
tail -f logs/combined.log
```

### Health Check
```bash
curl http://localhost:3000/api/health
```

## 🔒 Security Best Practices

1. **Set API Key**
   ```env
   API_KEY=your-secret-api-key
   ```

2. **Configure CORS**
   ```env
   CORS_ORIGINS=https://yourdomain.com,https://anotherdomain.com
   ```

3. **Use HTTPS in Production**
   ```env
   FORCE_HTTPS=true
   ```

4. **Rate Limiting**
   - Sudah built-in, dapat dikonfigurasi
   - Gunakan reverse proxy (nginx) untuk additional protection

## 🚀 Production Deployment

### 1. Setup dengan PM2

```bash
# Install PM2 globally
npm install -g pm2

# Start aplikasi
npm run pm2:start

# Monitor
pm2 monit

# Auto-restart on server reboot
pm2 startup
pm2 save
```

### 2. Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### 3. SSL/HTTPS dengan Let's Encrypt

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Get certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🛠️ Development

### Project Structure
```
whatsapp-multidevice-api/
├── server.js                 # Entry point
├── src/
│   ├── config/
│   │   └── config.js        # Konfigurasi aplikasi
│   ├── handlers/
│   │   └── SocketHandler.js # Socket.io handler
│   ├── managers/
│   │   └── WhatsAppManager.js # WhatsApp logic
│   └── routes/
│       └── api.js           # API routes
├── public/
│   └── index.html           # Dashboard web
├── sessions/                # WhatsApp sessions
├── qr-codes/               # QR code images
├── logs/                   # Log files
├── package.json
├── .env.example
├── ecosystem.config.js     # PM2 config
├── Dockerfile
└── docker-compose.yml
```

### Scripts

```bash
# Development
npm run dev

# Production
npm start

# Linting
npm run lint

# Format code
npm run format

# Clean data
npm run clean

# Tests
npm test
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Device Tidak Connect
- Pastikan WhatsApp Web tidak terbuka di browser lain
- Check apakah QR code sudah expire
- Restart device jika perlu

#### 2. QR Code Tidak Muncul
- Check permissions folder `qr-codes/`
- Pastikan device dalam status `initializing`
- Check logs untuk error

#### 3. Messages Tidak Terkirim
- Pastikan device dalam status `ready`
- Check format nomor WhatsApp: `6281234567890@c.us`
- Check rate limiting

#### 4. High Memory Usage
- Restart devices yang lama tidak digunakan
- Set `SESSION_TIMEOUT` lebih rendah
- Monitor dengan `pm2 monit`

### Debug Commands

```bash
# Check logs
tail -f logs/combined.log

# PM2 logs
pm2 logs whatsapp-api

# Check device sessions
ls -la sessions/

# Check QR codes
ls -la qr-codes/

# Memory usage
free -h
```

## 📝 Changelog

### v1.0.0
- ✅ Initial release
- ✅ Multi-device support
- ✅ REST API endpoints
- ✅ WebSocket real-time updates
- ✅ Web dashboard
- ✅ Docker support
- ✅ PM2 configuration

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
