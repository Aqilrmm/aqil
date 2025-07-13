// ecosystem.config.js
module.exports = {
    apps: [{
        name: 'whatsapp-api',
        script: 'server.js',
        instances: 1,
        autorestart: true,
        watch: false,
        max_memory_restart: '1G',
        env: {
            NODE_ENV: 'development',
            PORT: 7654
        },
        env_production: {
            NODE_ENV: 'production',
            PORT: 7654
        },
        log_date_format: 'YYYY-MM-DD HH:mm Z',
        error_file: './logs/err.log',
        out_file: './logs/out.log',
        log_file: './logs/combined.log',
        time: true
    }]
};