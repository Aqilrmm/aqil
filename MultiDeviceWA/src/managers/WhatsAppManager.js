// src/managers/WhatsAppManager.js
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode');
const fs = require('fs');
const path = require('path');
const EventEmitter = require('events');
const axios = require('axios');
class WhatsAppManager extends EventEmitter {
    constructor() {
        super();
        this.clients = new Map(); // Map<deviceId, Client>
        this.deviceStatus = new Map(); // Map<deviceId, status>
        this.qrCodes = new Map(); // Map<deviceId, qrData>
    }

    /**
     * Buat koneksi WhatsApp baru untuk device
     * @param {string} deviceId - ID unik untuk device
     */
    async createDevice(deviceId) {
        try {
            if (this.clients.has(deviceId)) {
                throw new Error(`Device ${deviceId} already exists`);
            }

            const client = new Client({
                authStrategy: new LocalAuth({
                    clientId: deviceId,
                    dataPath: path.join(__dirname, '../../sessions')
                }),
                puppeteer: {
                    headless: true,
                    args: [
                        '--no-sandbox',
                        '--disable-setuid-sandbox',
                        '--disable-dev-shm-usage',
                        '--disable-accelerated-2d-canvas',
                        '--no-first-run',
                        '--no-zygote',
                        '--single-process',
                        '--disable-gpu'
                    ]
                }
            });

            this.setupClientHandlers(client, deviceId);
            this.clients.set(deviceId, client);
            this.updateDeviceStatus(deviceId, 'initializing');

            await client.initialize();
            
            return {
                success: true,
                deviceId,
                message: 'Device initialization started'
            };
        } catch (error) {
            console.error(`Error creating device ${deviceId}:`, error);
            this.updateDeviceStatus(deviceId, 'error');
            throw error;
        }
    }

    /**
     * Setup event handlers untuk WhatsApp client
     */
    setupClientHandlers(client, deviceId) {
        client.on('qr', async (qr) => {
            try {
                console.log(`📱 QR Code generated for device: ${deviceId}`);
                
                // Generate QR code image
                const qrImagePath = path.join(__dirname, '../../qr-codes', `${deviceId}.png`);
                await qrcode.toFile(qrImagePath, qr);
                
                // Store QR data
                this.qrCodes.set(deviceId, {
                    qr,
                    imagePath: qrImagePath,
                    imageUrl: `/qr/${deviceId}.png`,
                    timestamp: new Date()
                });

                this.updateDeviceStatus(deviceId, 'qr_ready');
                this.emit('qr_generated', { deviceId, qr, imageUrl: `/qr/${deviceId}.png` });
            } catch (error) {
                console.error(`Error generating QR for ${deviceId}:`, error);
            }
        });

        client.on('ready', () => {
            console.log(`✅ Device ${deviceId} is ready!`);
            this.updateDeviceStatus(deviceId, 'ready');
            this.emit('device_ready', { deviceId });
            
            // Clean up QR code file
            this.cleanupQRCode(deviceId);
        });

        client.on('authenticated', () => {
            console.log(`🔐 Device ${deviceId} authenticated`);
            this.updateDeviceStatus(deviceId, 'authenticated');
            this.emit('device_authenticated', { deviceId });
        });

        client.on('auth_failure', (msg) => {
            console.error(`❌ Auth failure for device ${deviceId}:`, msg);
            this.updateDeviceStatus(deviceId, 'auth_failed');
            this.emit('auth_failure', { deviceId, message: msg });
        });

        client.on('disconnected', (reason) => {
            console.log(`📴 Device ${deviceId} disconnected:`, reason);
            this.updateDeviceStatus(deviceId, 'disconnected');
            this.emit('device_disconnected', { deviceId, reason });
        });

        client.on('message', async (message) => {
            try {
                const messageData = await this.formatMessage(message, deviceId);
                console.log(`📨 New message on device ${deviceId}:`, messageData);
                const ai_response = await this.sendMessageToAI(messageData);
                console.log(`🤖 AI response for device ${deviceId}:`, ai_response);
                const replyMessage = await this.sendMessage(deviceId, message.from, ai_response.response);
                this.emit('message_received', messageData);
            } catch (error) {
                console.error(`Error processing message on device ${deviceId}:`, error);
            }
        });
    }
    /**
     * Kirim pesan WhatsApp ke AI
     */
    async sendMessageToAI(messageData) {
        try {
            const buildMessage = {
                chat_id: messageData.chat.id + "_" + messageData.deviceId,
                character: messageData.deviceId,
                myname: messageData.sender.name,
                message: messageData.body
            };
            const response = await axios.post(`http://0.0.0.0:8435/chat`, buildMessage);
            return response.data;
        } catch (error) {
            console.error(`Error sending message to AI:`, error);
            throw error;
        }
    }

    /**
     * Format pesan WhatsApp untuk API
     */
    async formatMessage(message, deviceId) {
        const contact = await message.getContact();
        const chat = await message.getChat();
        
        return {
            deviceId,
            messageId: message.id._serialized,
            from: message.from,
            to: message.to,
            sender: {
                number: contact.number,
                name: contact.name || contact.pushname,
                isMe: contact.isMe
            },
            chat: {
                id: chat.id._serialized,
                name: chat.name,
                isGroup: chat.isGroup
            },
            body: message.body,
            type: message.type,
            timestamp: message.timestamp,
            hasMedia: message.hasMedia,
            isForwarded: message.isForwarded,
            isStatus: message.isStatus,
            broadcast: message.broadcast
        };
    }

    /**
     * Kirim pesan melalui device tertentu
     */
    async sendMessage(deviceId, to, message) {
        try {
            const client = this.clients.get(deviceId);
            if (!client) {
                throw new Error(`Device ${deviceId} not found`);
            }

            const status = this.deviceStatus.get(deviceId);
            console.log('Status object:', this.deviceStatus.get(deviceId));
            if (status.status !== 'ready') {
                throw new Error(`Device ${deviceId} is not ready. Current status: ${status}`);
            }

            const result = await client.sendMessage(to, message);
            
            return {
                success: true,
                messageId: result.id._serialized,
                deviceId,
                to,
                message,
                timestamp: new Date()
            };
        } catch (error) {
            console.error(`Error sending message via device ${deviceId}:`, error);
            throw error;
        }
    }

    /**
     * Balas pesan
     */
    async replyMessage(deviceId, messageId, reply) {
        try {
            const client = this.clients.get(deviceId);
            if (!client) {
                throw new Error(`Device ${deviceId} not found`);
            }

            // Get message by ID and reply
            const message = await client.getMessageById(messageId);
            if (!message) {
                throw new Error(`Message ${messageId} not found`);
            }

            const result = await message.reply(reply);
            
            return {
                success: true,
                originalMessageId: messageId,
                replyMessageId: result.id._serialized,
                deviceId,
                reply,
                timestamp: new Date()
            };
        } catch (error) {
            console.error(`Error replying message via device ${deviceId}:`, error);
            throw error;
        }
    }

    /**
     * Hapus device
     */
    async removeDevice(deviceId) {
        try {
            const client = this.clients.get(deviceId);
            if (client) {
                await client.logout();
                await client.destroy();
                this.clients.delete(deviceId);
            }

            this.deviceStatus.delete(deviceId);
            this.cleanupQRCode(deviceId);
            
            // Remove session files
            const sessionPath = path.join(__dirname, '../../sessions', `session-${deviceId}`);
            if (fs.existsSync(sessionPath)) {
                fs.rmSync(sessionPath, { recursive: true, force: true });
            }

            this.emit('device_removed', { deviceId });
            
            return {
                success: true,
                deviceId,
                message: 'Device removed successfully'
            };
        } catch (error) {
            console.error(`Error removing device ${deviceId}:`, error);
            throw error;
        }
    }

    /**
     * Update status device
     */
    updateDeviceStatus(deviceId, status) {
        this.deviceStatus.set(deviceId, {
            status,
            timestamp: new Date(),
            deviceId
        });
        
        this.emit('status_updated', {
            deviceId,
            status,
            timestamp: new Date()
        });
    }

    /**
     * Get status semua device
     */
    getAllDevicesStatus() {
        const devices = [];
        for (const [deviceId, statusData] of this.deviceStatus.entries()) {
            devices.push({
                deviceId,
                ...statusData,
                hasQR: this.qrCodes.has(deviceId)
            });
        }
        return devices;
    }

    /**
     * Get QR code untuk device
     */
    getQRCode(deviceId) {
        return this.qrCodes.get(deviceId);
    }

    /**
     * Cleanup QR code file
     */
    cleanupQRCode(deviceId) {
        const qrData = this.qrCodes.get(deviceId);
        if (qrData && qrData.imagePath && fs.existsSync(qrData.imagePath)) {
            fs.unlinkSync(qrData.imagePath);
        }
        this.qrCodes.delete(deviceId);
    }

    /**
     * Get device info
     */
    async getDeviceInfo(deviceId) {
        try {
            const client = this.clients.get(deviceId);
            if (!client) {
                throw new Error(`Device ${deviceId} not found`);
            }

            const info = client.info;
            if (!info) {
                throw new Error(`Device ${deviceId} not ready`);
            }

            return {
                deviceId,
                phone: info.wid.user,
                name: info.pushname,
                platform: info.platform,
                battery: info.battery,
                plugged: info.plugged,
                status: this.deviceStatus.get(deviceId)?.status
            };
        } catch (error) {
            console.error(`Error getting device info ${deviceId}:`, error);
            throw error;
        }
    }
}

module.exports = WhatsAppManager;