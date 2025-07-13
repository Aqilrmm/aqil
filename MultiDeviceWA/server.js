// server.js
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const path = require('path');
const fs = require('fs');

// Import modules
const WhatsAppManager = require('./src/managers/WhatsAppManager');
const ApiRoutes = require('./src/routes/api');
const SocketHandler = require('./src/handlers/SocketHandler');
const config = require('./src/config/config');

class WhatsAppServer {
    constructor() {
        this.app = express();
        this.server = http.createServer(this.app);
        this.io = socketIo(this.server, {
            cors: {
                origin: "*",
                methods: ["GET", "POST"]
            }
        });
        
        this.whatsappManager = new WhatsAppManager();
        this.socketHandler = new SocketHandler(this.io, this.whatsappManager);
        
        this.setupMiddleware();
        this.setupRoutes();
        this.setupSocketHandlers();
        this.createDirectories();
    }

    setupMiddleware() {
        this.app.use(cors());
        this.app.use(express.json());
        this.app.use(express.urlencoded({ extended: true }));
        this.app.use('/qr', express.static(path.join(__dirname, 'qr-codes')));
        
        // Logging middleware
        this.app.use((req, res, next) => {
            console.log(`${new Date().toISOString()} - ${req.method} ${req.path}`);
            next();
        });
    }

    setupRoutes() {
        this.app.use('/api', new ApiRoutes(this.whatsappManager, this.io).getRouter());
        
        // Serve simple dashboard
        this.app.get('/', (req, res) => {
            res.sendFile(path.join(__dirname, 'public', 'index.html'));
        });
    }

    setupSocketHandlers() {
        this.socketHandler.setupHandlers();
    }

    createDirectories() {
        const dirs = ['qr-codes', 'sessions', 'public'];
        dirs.forEach(dir => {
            if (!fs.existsSync(dir)) {
                fs.mkdirSync(dir, { recursive: true });
            }
        });
    }

    start() {
        this.server.listen(config.PORT, () => {
            console.log(`🚀 WhatsApp Multi-Device Server running on port ${config.PORT}`);
            console.log(`📱 Dashboard: http://localhost:${config.PORT}`);
            console.log(`🔗 API Base URL: http://localhost:${config.PORT}/api`);
        });
    }
}

// Start server
const server = new WhatsAppServer();
server.start();

module.exports = WhatsAppServer;