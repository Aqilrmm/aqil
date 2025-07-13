// src/routes/api.js
const express = require("express");
const rateLimit = require("express-rate-limit");

class ApiRoutes {
  constructor(whatsappManager, io) {
    this.whatsappManager = whatsappManager;
    this.io = io;
    this.router = express.Router();
    this.setupRateLimit();
    this.setupRoutes();
    this.setupWebhookHandlers();
  }

  setupRateLimit() {
    // Rate limiting untuk API
    this.limiter = rateLimit({
      windowMs: 1 * 60 * 1000, // 1 menit
      max: 100, // maksimal 100 request per menit
      message: {
        success: false,
        error: "Too many requests, please try again later",
      },
    });

    this.messageLimiter = rateLimit({
      windowMs: 1 * 60 * 1000, // 1 menit
      max: 30, // maksimal 30 pesan per menit
      message: {
        success: false,
        error: "Message rate limit exceeded",
      },
    });
  }

  setupRoutes() {
    this.router.use(this.limiter);

    // Health check
    this.router.get("/health", this.getHealth.bind(this));

    // Device management
    this.router.post("/devices", this.createDevice.bind(this));
    this.router.get("/devices", this.getDevices.bind(this));
    this.router.get("/devices/:deviceId", this.getDevice.bind(this));
    this.router.get("/devices/:deviceId/qr", this.getQRCode.bind(this));
    this.router.delete("/devices/:deviceId", this.removeDevice.bind(this));

    // Messaging
    this.router.post("/send", this.messageLimiter, this.sendMessage.bind(this));
    this.router.post(
      "/reply",
      this.messageLimiter,
      this.replyMessage.bind(this)
    );

    // Webhook untuk pesan masuk (opsional)
    this.router.post("/webhook", this.setWebhook.bind(this));
    this.router.get("/webhook", this.getWebhook.bind(this));
  }

  setupWebhookHandlers() {
    // Forward pesan ke webhook jika diset
    this.whatsappManager.on("message_received", async (messageData) => {
      if (this.webhookUrl) {
        try {
          const fetch = (await import("node-fetch")).default;
          await fetch(this.webhookUrl, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              type: "message_received",
              data: messageData,
              timestamp: new Date(),
            }),
          });
        } catch (error) {
          console.error("Webhook error:", error);
        }
      }

      // Emit ke socket.io
      this.io.emit("message_received", messageData);
    });
  }

  /**
   * GET /api/health
   * Health check endpoint
   */
  async getHealth(req, res) {
    try {
      const devices = this.whatsappManager.getAllDevicesStatus();
      const readyDevices = devices.filter((d) => d.status === "ready").length;

      res.json({
        success: true,
        status: "healthy",
        timestamp: new Date(),
        stats: {
          totalDevices: devices.length,
          readyDevices,
          uptime: process.uptime(),
        },
      });
    } catch (error) {
      res.status(500).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * POST /api/devices
   * Buat device WhatsApp baru
   */
  async createDevice(req, res) {
    try {
      const { deviceId } = req.body;

      if (!deviceId) {
        return res.status(400).json({
          success: false,
          error: "deviceId is required",
        });
      }

      const result = await this.whatsappManager.createDevice(deviceId);

      res.json({
        success: true,
        data: result,
        message:
          "Device creation initiated. Check QR code endpoint or listen to socket events.",
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * GET /api/devices
   * Get status semua device
   */
  async getDevices(req, res) {
    try {
      const devices = this.whatsappManager.getAllDevicesStatus();

      res.json({
        success: true,
        data: {
          devices,
          total: devices.length,
          ready: devices.filter((d) => d.status === "ready").length,
        },
      });
    } catch (error) {
      res.status(500).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * GET /api/devices/:deviceId
   * Get info device tertentu
   */
  async getDevice(req, res) {
    try {
      const { deviceId } = req.params;
      const deviceInfo = await this.whatsappManager.getDeviceInfo(deviceId);

      res.json({
        success: true,
        data: deviceInfo,
      });
    } catch (error) {
      if (error.message.includes("not ready")) {
        return res.status(503).json({
          success: false,
          error: "DEVICE_NOT_READY",
          message: `Device ${req.params.deviceId} belum siap.`,
        });
      }

      if (error.message.includes("not found")) {
        return res.status(404).json({
          success: false,
          error: "DEVICE_NOT_FOUND",
          message: `Device ${req.params.deviceId} nggak ditemukan.`,
        });
      }

      // fallback unknown error
      res.status(500).json({
        success: false,
        error: "INTERNAL_ERROR",
        message: error.message,
      });
    }
  }

  /**
   * GET /api/devices/:deviceId/qr
   * Get QR code untuk device
   */
  async getQRCode(req, res) {
    try {
      const { deviceId } = req.params;
      const qrData = this.whatsappManager.getQRCode(deviceId);

      if (!qrData) {
        return res.status(404).json({
          success: false,
          error:
            "QR code not available. Device might be already connected or not initializing.",
        });
      }

      res.json({
        success: true,
        data: {
          deviceId,
          qrImageUrl: `${req.protocol}://${req.get("host")}${qrData.imageUrl}`,
          qrCode: qrData.qr,
          timestamp: qrData.timestamp,
        },
      });
    } catch (error) {
      res.status(404).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * DELETE /api/devices/:deviceId
   * Hapus device
   */
  async removeDevice(req, res) {
    try {
      const { deviceId } = req.params;
      const result = await this.whatsappManager.removeDevice(deviceId);

      res.json({
        success: true,
        data: result,
      });
    } catch (error) {
      res.status(404).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * POST /api/send
   * Kirim pesan
   */
  async sendMessage(req, res) {
    try {
      const { deviceId, to, message } = req.body;

      if (!deviceId || !to || !message) {
        return res.status(400).json({
          success: false,
          error: "deviceId, to, and message are required",
        });
      }

      const result = await this.whatsappManager.sendMessage(
        deviceId,
        to,
        message
      );

      res.json({
        success: true,
        data: result,
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * POST /api/reply
   * Balas pesan
   */
  async replyMessage(req, res) {
    try {
      const { deviceId, messageId, reply } = req.body;

      if (!deviceId || !messageId || !reply) {
        return res.status(400).json({
          success: false,
          error: "deviceId, messageId, and reply are required",
        });
      }

      const result = await this.whatsappManager.replyMessage(
        deviceId,
        messageId,
        reply
      );

      res.json({
        success: true,
        data: result,
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * POST /api/webhook
   * Set webhook URL untuk menerima pesan
   */
  async setWebhook(req, res) {
    try {
      const { url } = req.body;

      if (!url) {
        return res.status(400).json({
          success: false,
          error: "url is required",
        });
      }

      this.webhookUrl = url;

      res.json({
        success: true,
        message: "Webhook URL set successfully",
        webhookUrl: url,
      });
    } catch (error) {
      res.status(400).json({
        success: false,
        error: error.message,
      });
    }
  }

  /**
   * GET /api/webhook
   * Get webhook URL
   */
  async getWebhook(req, res) {
    res.json({
      success: true,
      webhookUrl: this.webhookUrl || null,
    });
  }

  getRouter() {
    return this.router;
  }
}

module.exports = ApiRoutes;
