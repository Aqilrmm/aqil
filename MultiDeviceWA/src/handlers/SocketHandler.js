// src/handlers/SocketHandler.js
class SocketHandler {
    constructor(io, whatsappManager) {
        this.io = io;
        this.whatsappManager = whatsappManager;
        this.connectedClients = new Map(); // Map<socketId, clientInfo>
    }

    setupHandlers() {
        this.io.on('connection', (socket) => {
            console.log(`🔌 Client connected: ${socket.id}`);
            this.connectedClients.set(socket.id, {
                socketId: socket.id,
                connectedAt: new Date()
            });

            // Send welcome message with current status
            this.sendWelcomeMessage(socket);

            // Handle device creation
            socket.on('create_device', async (data) => {
                try {
                    console.log(`📱 Creating device: ${data.deviceId}`);
                    await this.whatsappManager.createDevice(data.deviceId);
                    socket.emit('device_created', { deviceId: data.deviceId });
                } catch (error) {
                    console.error(`❌ Error creating device ${data.deviceId}:`, error);
                    socket.emit('error', { message: error.message });
                }
            });

            // Handle device removal
            socket.on('remove_device', async (data) => {
                try {
                    console.log(`🗑️ Removing device: ${data.deviceId}`);
                    await this.whatsappManager.removeDevice(data.deviceId);
                    socket.emit('device_removed', { deviceId: data.deviceId });
                    this.broadcastDevicesStatus();
                } catch (error) {
                    console.error(`❌ Error removing device ${data.deviceId}:`, error);
                    socket.emit('error', { message: error.message });
                }
            });

            // Handle message sending
            socket.on('send_message', async (data) => {
                try {
                    console.log(`💬 Sending message via device ${data.deviceId}`);
                    const result = await this.whatsappManager.sendMessage(
                        data.deviceId, 
                        data.to, 
                        data.message
                    );
                    socket.emit('message_sent', result);
                } catch (error) {
                    console.error(`❌ Error sending message:`, error);
                    socket.emit('error', { message: error.message });
                }
            });

            // Handle reply message
            socket.on('reply_message', async (data) => {
                try {
                    console.log(`↩️ Replying to message ${data.messageId}`);
                    const result = await this.whatsappManager.replyMessage(
                        data.deviceId,
                        data.messageId,
                        data.reply
                    );
                    socket.emit('message_sent', result);
                } catch (error) {
                    console.error(`❌ Error replying message:`, error);
                    socket.emit('error', { message: error.message });
                }
            });

            // Handle get devices status
            socket.on('get_devices_status', () => {
                this.sendDevicesStatus(socket);
            });

            // Handle get QR code
            socket.on('get_qr_code', (data) => {
                try {
                    const qrData = this.whatsappManager.getQRCode(data.deviceId);
                    if (qrData) {
                        socket.emit('qr_code', {
                            deviceId: data.deviceId,
                            qrImageUrl: `http://localhost:3000${qrData.imageUrl}`,
                            qrCode: qrData.qr,
                            timestamp: qrData.timestamp
                        });
                    } else {
                        socket.emit('error', { 
                            message: 'QR code not available for this device' 
                        });
                    }
                } catch (error) {
                    socket.emit('error', { message: error.message });
                }
            });

            // Handle disconnect
            socket.on('disconnect', () => {
                console.log(`🔌 Client disconnected: ${socket.id}`);
                this.connectedClients.delete(socket.id);
            });
        });

        // Setup WhatsApp Manager event handlers
        this.setupWhatsAppEventHandlers();
    }

    setupWhatsAppEventHandlers() {
        // QR Code generated
        this.whatsappManager.on('qr_generated', (data) => {
            console.log(`📱 Broadcasting QR code for device: ${data.deviceId}`);
            this.io.emit('qr_code', {
                deviceId: data.deviceId,
                qrImageUrl: `http://localhost:3000${data.imageUrl}`,
                qrCode: data.qr,
                timestamp: new Date()
            });
        });

        // Device ready
        this.whatsappManager.on('device_ready', (data) => {
            console.log(`✅ Broadcasting device ready: ${data.deviceId}`);
            this.io.emit('device_status', {
                deviceId: data.deviceId,
                status: 'ready',
                timestamp: new Date()
            });
            this.broadcastDevicesStatus();
        });

        // Device authenticated
        this.whatsappManager.on('device_authenticated', (data) => {
            console.log(`🔐 Broadcasting device authenticated: ${data.deviceId}`);
            this.io.emit('device_status', {
                deviceId: data.deviceId,
                status: 'authenticated',
                timestamp: new Date()
            });
        });

        // Auth failure
        this.whatsappManager.on('auth_failure', (data) => {
            console.log(`❌ Broadcasting auth failure: ${data.deviceId}`);
            this.io.emit('device_status', {
                deviceId: data.deviceId,
                status: 'auth_failed',
                timestamp: new Date(),
                error: data.message
            });
            this.broadcastDevicesStatus();
        });

        // Device disconnected
        this.whatsappManager.on('device_disconnected', (data) => {
            console.log(`📴 Broadcasting device disconnected: ${data.deviceId}`);
            this.io.emit('device_status', {
                deviceId: data.deviceId,
                status: 'disconnected',
                timestamp: new Date(),
                reason: data.reason
            });
            this.broadcastDevicesStatus();
        });

        // Message received
        this.whatsappManager.on('message_received', (data) => {
            console.log(`📨 Broadcasting message received from device: ${data.deviceId}`);
            this.io.emit('message_received', data);
        });

        // Device removed
        this.whatsappManager.on('device_removed', (data) => {
            console.log(`🗑️ Broadcasting device removed: ${data.deviceId}`);
            this.io.emit('device_removed', data);
            this.broadcastDevicesStatus();
        });

        // Status updated
        this.whatsappManager.on('status_updated', (data) => {
            this.io.emit('device_status', data);
        });
    }

    sendWelcomeMessage(socket) {
        const devices = this.whatsappManager.getAllDevicesStatus();
        const stats = {
            totalDevices: devices.length,
            readyDevices: devices.filter(d => d.status === 'ready').length,
            connectedClients: this.connectedClients.size,
            uptime: process.uptime()
        };

        socket.emit('welcome', {
            message: 'Connected to WhatsApp Multi-Device API',
            timestamp: new Date(),
            stats,
            devices
        });
    }

    sendDevicesStatus(socket) {
        const devices = this.whatsappManager.getAllDevicesStatus();
        socket.emit('devices_status', { devices });
    }

    broadcastDevicesStatus() {
        const devices = this.whatsappManager.getAllDevicesStatus();
        this.io.emit('devices_status', { devices });
    }

    // Broadcast message to all connected clients
    broadcast(event, data) {
        this.io.emit(event, data);
    }

    // Send message to specific socket
    sendToSocket(socketId, event, data) {
        const socket = this.io.to(socketId);
        if (socket) {
            socket.emit(event, data);
        }
    }

    // Get connected clients info
    getConnectedClients() {
        return Array.from(this.connectedClients.values());
    }
}

module.exports = SocketHandler;