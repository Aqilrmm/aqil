// src/config/config.js
const config = {
    // Server configuration
    PORT: process.env.PORT || 3000,
    BASE_URL: process.env.BASE_URL || 'http://localhost:3000',
    
    // WhatsApp configuration
    WHATSAPP: {
        // Session timeout in milliseconds (default: 30 minutes)
        SESSION_TIMEOUT: parseInt(process.env.SESSION_TIMEOUT) || 30 * 60 * 1000,
        
        // Maximum devices allowed
        MAX_DEVICES: parseInt(process.env.MAX_DEVICES) || 10,
        
        // QR code expiry time in milliseconds (default: 5 minutes)
        QR_EXPIRY_TIME: parseInt(process.env.QR_EXPIRY_TIME) || 5 * 60 * 1000,
        
        // Puppeteer launch options
        PUPPETEER_OPTIONS: {
            headless: process.env.NODE_ENV === 'production',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--single-process',
                '--disable-gpu',
                '--disable-web-security',
                '--disable-features=VizDisplayCompositor'
            ]
        }
    },
    
    // Rate limiting configuration
    RATE_LIMIT: {
        // General API rate limit
        WINDOW_MS: parseInt(process.env.RATE_LIMIT_WINDOW) || 60 * 1000, // 1 minute
        MAX_REQUESTS: parseInt(process.env.RATE_LIMIT_MAX) || 100,
        
        // Message sending rate limit
        MESSAGE_WINDOW_MS: parseInt(process.env.MESSAGE_RATE_LIMIT_WINDOW) || 60 * 1000,
        MESSAGE_MAX_REQUESTS: parseInt(process.env.MESSAGE_RATE_LIMIT_MAX) || 30
    },
    
    // Logging configuration
    LOGGING: {
        LEVEL: process.env.LOG_LEVEL || 'info',
        ENABLE_ACCESS_LOG: process.env.ENABLE_ACCESS_LOG === 'true',
        LOG_FILE: process.env.LOG_FILE || null
    },
    
    // Security configuration
    SECURITY: {
        // API Key for webhook authentication (optional)
        API_KEY: process.env.API_KEY || null,
        
        // CORS origins (comma separated)
        CORS_ORIGINS: process.env.CORS_ORIGINS ? process.env.CORS_ORIGINS.split(',') : ['*'],
        
        // Enable HTTPS redirect
        FORCE_HTTPS: process.env.FORCE_HTTPS === 'true'
    },
    
    // Webhook configuration
    WEBHOOK: {
        // Default webhook timeout in milliseconds
        TIMEOUT: parseInt(process.env.WEBHOOK_TIMEOUT) || 10000,
        
        // Retry attempts for failed webhooks
        RETRY_ATTEMPTS: parseInt(process.env.WEBHOOK_RETRY_ATTEMPTS) || 3,
        
        // Delay between retries in milliseconds
        RETRY_DELAY: parseInt(process.env.WEBHOOK_RETRY_DELAY) || 1000
    },
    
    // File storage configuration
    STORAGE: {
        // Directory for QR codes
        QR_DIR: process.env.QR_DIR || 'qr-codes',
        
        // Directory for session files
        SESSION_DIR: process.env.SESSION_DIR || 'sessions',
        
        // Cleanup old QR codes after (milliseconds)
        QR_CLEANUP_AFTER: parseInt(process.env.QR_CLEANUP_AFTER) || 24 * 60 * 60 * 1000, // 24 hours
        
        // Maximum file size for uploads (bytes)
        MAX_FILE_SIZE: parseInt(process.env.MAX_FILE_SIZE) || 10 * 1024 * 1024 // 10MB
    },
    
    // Database configuration (if using database)
    DATABASE: {
        URL: process.env.DATABASE_URL || null,
        MAX_CONNECTIONS: parseInt(process.env.DB_MAX_CONNECTIONS) || 10
    },
    
    // Environment
    NODE_ENV: process.env.NODE_ENV || 'development',
    
    // Development mode helpers
    isDevelopment: function() {
        return this.NODE_ENV === 'development';
    },
    
    isProduction: function() {
        return this.NODE_ENV === 'production';
    }
};

module.exports = config;