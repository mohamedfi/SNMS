# Deployment Blueprint - SNMS

## Deployment Architecture

### Production Environment Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     Load Balancer / CDN                      │
│                   (CloudFlare / AWS CloudFront)              │
└───────────────┬─────────────────────────┬───────────────────┘
                │                         │
        ┌───────┴────────┐        ┌──────┴────────┐
        │   Web Server 1  │        │  Web Server 2  │
        │   (Nginx)       │        │  (Nginx)       │
        │   React Apps    │        │  React Apps    │
        └───────┬────────┘        └──────┬────────┘
                │                         │
        ┌───────┴─────────────────────────┴──────────┐
        │       Application Servers                   │
        │       Laravel API (PHP-FPM)                 │
        │       (Auto-scaling Group)                  │
        └───────┬─────────────────────────────────────┘
                │
        ┌───────┴──────────────────────────────────────┐
        │                                              │
    ┌───┴───┐  ┌──────┐  ┌────────┐  ┌──────────┐
    │ MySQL │  │ Redis│  │ S3/DO  │  │  Queue   │
    │ (RDS) │  │Cache │  │ Spaces │  │ Worker   │
    └───────┘  └──────┘  └────────┘  └──────────┘
```

---

## 1. Server Requirements

### Minimum Production Requirements

**Application Server**:
- **CPU**: 4 vCPUs
- **RAM**: 8 GB
- **Storage**: 50 GB SSD
- **OS**: Ubuntu 22.04 LTS or newer
- **PHP**: 8.3+
- **Web Server**: Nginx 1.24+
- **Database**: MySQL 8.0+
- **Cache**: Redis 7.0+
- **Queue**: Redis or AWS SQS

**Database Server** (if separate):
- **CPU**: 2-4 vCPUs
- **RAM**: 8-16 GB
- **Storage**: 100 GB SSD (with auto-scaling)

**Recommended Cloud Providers**:
- **DigitalOcean** (Droplets + Managed Database + Spaces)
- **AWS** (EC2 + RDS + S3 + ElastiCache)
- **Google Cloud Platform**
- **Azure**

---

## 2. Technology Stack (Deployed)

### Backend
- **Runtime**: PHP 8.3 with FPM
- **Framework**: Laravel 12.x
- **Web Server**: Nginx (reverse proxy + static files)
- **Database**: MySQL 8.0 (managed or self-hosted)
- **Cache**: Redis (session + cache)
- **Queue**: Redis (or AWS SQS for scale)
- **Storage**: DigitalOcean Spaces / AWS S3
- **Process Manager**: Supervisor (for queue workers)

### Frontend
- **Build**: Vite (production build)
- **Hosting**: Nginx (serve static files) or CDN
- **CDN**: CloudFlare / AWS CloudFront (optional)

### Infrastructure
- **Container**: Docker (optional, recommended)
- **Orchestration**: Docker Compose or Kubernetes (for large scale)
- **CI/CD**: GitHub Actions / GitLab CI
- **Monitoring**: New Relic / DataDog / Sentry
- **Backups**: Automated daily backups

---

## 3. Environment Configuration

### 3.1 Laravel .env (Production)

```env
# Application
APP_NAME="Steps Nursery Management System"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://api.stepsnursery.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql-prod.example.com
DB_PORT=3306
DB_DATABASE=snms_production
DB_USERNAME=snms_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis-prod.example.com
REDIS_PASSWORD=REDIS_PASSWORD_HERE
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=SMTP_USERNAME
MAIL_PASSWORD=SMTP_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stepsnursery.com
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=s3

# AWS S3 or DigitalOcean Spaces
AWS_ACCESS_KEY_ID=YOUR_ACCESS_KEY
AWS_SECRET_ACCESS_KEY=YOUR_SECRET_KEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=snms-production
AWS_USE_PATH_STYLE_ENDPOINT=false
AWS_ENDPOINT=https://nyc3.digitaloceanspaces.com  # For DO Spaces
AWS_URL=https://snms-production.nyc3.cdn.digitaloceanspaces.com

# Laravel Sanctum
SANCTUM_STATEFUL_DOMAINS=admin.stepsnursery.com,parent.stepsnursery.com
SESSION_DOMAIN=.stepsnursery.com

# CORS
CORS_ALLOWED_ORIGINS=https://admin.stepsnursery.com,https://parent.stepsnursery.com

# Payment Gateway (Paymob)
PAYMOB_API_KEY=YOUR_PAYMOB_API_KEY
PAYMOB_INTEGRATION_ID=YOUR_INTEGRATION_ID
PAYMOB_IFRAME_ID=YOUR_IFRAME_ID

# Payment Gateway (Stripe - Alternative)
STRIPE_KEY=pk_live_YOUR_STRIPE_KEY
STRIPE_SECRET=sk_live_YOUR_STRIPE_SECRET

# WhatsApp API
WHATSAPP_API_URL=https://api.whatsapp.com/send
WHATSAPP_API_KEY=YOUR_WHATSAPP_API_KEY
WHATSAPP_PHONE_NUMBER_ID=YOUR_PHONE_NUMBER_ID

# Monitoring
SENTRY_LARAVEL_DSN=https://YOUR_SENTRY_DSN@sentry.io/PROJECT_ID

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL
```

### 3.2 React Apps .env (Production)

**Admin Dashboard**:
```env
VITE_APP_NAME="SNMS Admin Dashboard"
VITE_API_BASE_URL=https://api.stepsnursery.com/api/v1
VITE_APP_URL=https://admin.stepsnursery.com
VITE_STORAGE_URL=https://snms-production.nyc3.cdn.digitaloceanspaces.com
```

**Parent App**:
```env
VITE_APP_NAME="Steps Nursery - Parent Portal"
VITE_API_BASE_URL=https://api.stepsnursery.com/api/v1
VITE_APP_URL=https://parent.stepsnursery.com
VITE_STORAGE_URL=https://snms-production.nyc3.cdn.digitaloceanspaces.com
```

---

## 4. Nginx Configuration

### 4.1 Laravel API Server Block

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name api.stepsnursery.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.stepsnursery.com;

    root /var/www/snms/backend/public;
    index index.php;

    # SSL Certificates (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/api.stepsnursery.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.stepsnursery.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    # Client Max Body Size (for file uploads)
    client_max_body_size 20M;

    # Logging
    access_log /var/log/nginx/api.stepsnursery.com.access.log;
    error_log /var/log/nginx/api.stepsnursery.com.error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4.2 React Admin Dashboard Server Block

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name admin.stepsnursery.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name admin.stepsnursery.com;

    root /var/www/snms/frontend/admin-dashboard/dist;
    index index.html;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/admin.stepsnursery.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/admin.stepsnursery.com/privkey.pem;

    # Security Headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 4.3 React Parent App Server Block

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name parent.stepsnursery.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name parent.stepsnursery.com;

    root /var/www/snms/frontend/parent-app/dist;
    index index.html;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/parent.stepsnursery.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/parent.stepsnursery.com/privkey.pem;

    # Security Headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Service Worker (no cache)
    location = /service-worker.js {
        expires off;
        add_header Cache-Control "no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0";
    }
}
```

---

## 5. Docker Configuration (Optional)

### 5.1 Docker Compose (Production-Ready)

```yaml
version: '3.8'

services:
  # Nginx Web Server
  nginx:
    image: nginx:alpine
    container_name: snms_nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./backend:/var/www/snms/backend
      - ./frontend/admin-dashboard/dist:/var/www/snms/frontend/admin-dashboard/dist
      - ./frontend/parent-app/dist:/var/www/snms/frontend/parent-app/dist
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
      - ./docker/nginx/ssl:/etc/nginx/ssl
    depends_on:
      - php
    networks:
      - snms_network
    restart: unless-stopped

  # PHP-FPM
  php:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: snms_php
    volumes:
      - ./backend:/var/www/snms/backend
    environment:
      - PHP_FPM_USER=www-data
      - PHP_FPM_GROUP=www-data
    depends_on:
      - mysql
      - redis
    networks:
      - snms_network
    restart: unless-stopped

  # MySQL Database
  mysql:
    image: mysql:8.0
    container_name: snms_mysql
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
    ports:
      - "3306:3306"
    networks:
      - snms_network
    restart: unless-stopped

  # Redis Cache
  redis:
    image: redis:7-alpine
    container_name: snms_redis
    command: redis-server --requirepass ${REDIS_PASSWORD}
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    networks:
      - snms_network
    restart: unless-stopped

  # Queue Worker
  queue:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: snms_queue
    command: php /var/www/snms/backend/artisan queue:work --sleep=3 --tries=3 --max-time=3600
    volumes:
      - ./backend:/var/www/snms/backend
    depends_on:
      - mysql
      - redis
    networks:
      - snms_network
    restart: unless-stopped

  # Scheduler
  scheduler:
    build:
      context: ./docker/php
      dockerfile: Dockerfile
    container_name: snms_scheduler
    command: /bin/sh -c "while [ true ]; do php /var/www/snms/backend/artisan schedule:run --verbose --no-interaction & sleep 60; done"
    volumes:
      - ./backend:/var/www/snms/backend
    depends_on:
      - mysql
      - redis
    networks:
      - snms_network
    restart: unless-stopped

volumes:
  mysql_data:
  redis_data:

networks:
  snms_network:
    driver: bridge
```

### 5.2 PHP Dockerfile

```dockerfile
FROM php:8.3-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zip \
    unzip \
    bash \
    mysql-client \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd pdo pdo_mysql bcmath opcache exif

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/snms/backend

# Copy PHP configuration
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create user
RUN addgroup -g 1000 www && adduser -D -u 1000 -G www www

# Change ownership
RUN chown -R www:www /var/www/snms

USER www

EXPOSE 9000

CMD ["php-fpm"]
```

---

## 6. SSL Certificates (Let's Encrypt)

### Install Certbot

```bash
sudo apt update
sudo apt install certbot python3-certbot-nginx
```

### Generate Certificates

```bash
# API
sudo certbot --nginx -d api.stepsnursery.com

# Admin Dashboard
sudo certbot --nginx -d admin.stepsnursery.com

# Parent App
sudo certbot --nginx -d parent.stepsnursery.com
```

### Auto-Renewal

Certbot automatically adds a cron job for renewal. Verify:

```bash
sudo systemctl status certbot.timer
```

---

## 7. Deployment Process

### 7.1 Initial Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server redis-server php8.3-fpm php8.3-mysql php8.3-redis php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd supervisor git unzip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js & npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 7.2 Laravel Deployment

```bash
# Clone repository
cd /var/www
git clone https://github.com/yourorg/snms.git
cd snms/backend

# Install dependencies
composer install --optimize-autoloader --no-dev

# Setup environment
cp .env.example .env
nano .env  # Edit configuration

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=SettingsSeeder

# Link storage
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data /var/www/snms/backend
sudo chmod -R 755 /var/www/snms/backend
sudo chmod -R 775 /var/www/snms/backend/storage
sudo chmod -R 775 /var/www/snms/backend/bootstrap/cache
```

### 7.3 React Apps Deployment

```bash
# Admin Dashboard
cd /var/www/snms/frontend/admin-dashboard
cp .env.example .env.production
nano .env.production  # Edit API URL
npm install
npm run build

# Parent App
cd /var/www/snms/frontend/parent-app
cp .env.example .env.production
nano .env.production  # Edit API URL
npm install
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/snms/frontend
```

---

## 8. Queue Worker & Scheduler Setup

### 8.1 Supervisor Configuration

Create `/etc/supervisor/conf.d/snms-worker.conf`:

```ini
[program:snms-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/snms/backend/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/snms/backend/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start snms-worker:*
```

### 8.2 Cron Job for Scheduler

```bash
sudo crontab -e -u www-data
```

Add:

```cron
* * * * * cd /var/www/snms/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 9. Monitoring & Logging

### 9.1 Application Monitoring

**Sentry Integration** (already in .env):
- Automatic error tracking
- Performance monitoring
- Release tracking

**Laravel Telescope** (Development only):
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### 9.2 Server Monitoring

**Tools**:
- **Uptime Robot** - Monitor uptime
- **New Relic** - Application performance
- **Prometheus + Grafana** - Server metrics
- **LogDNA / Papertrail** - Log aggregation

### 9.3 Log Rotation

Create `/etc/logrotate.d/snms`:

```
/var/www/snms/backend/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 10. Backup Strategy

### 10.1 Database Backup

Create `/usr/local/bin/snms-backup.sh`:

```bash
#!/bin/bash

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/var/backups/snms"
DB_NAME="snms_production"
DB_USER="snms_user"
DB_PASS="YOUR_DB_PASSWORD"

# Create backup directory
mkdir -p $BACKUP_DIR

# Dump database
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$TIMESTAMP.sql.gz

# Delete backups older than 30 days
find $BACKUP_DIR -name "db_*.sql.gz" -type f -mtime +30 -delete

# Upload to S3 (optional)
# aws s3 cp $BACKUP_DIR/db_$TIMESTAMP.sql.gz s3://snms-backups/database/
```

Make executable and add to cron:

```bash
sudo chmod +x /usr/local/bin/snms-backup.sh
sudo crontab -e
```

Add:

```cron
0 2 * * * /usr/local/bin/snms-backup.sh
```

### 10.2 File Storage Backup

If using S3/DO Spaces, enable versioning and lifecycle policies.

---

## 11. CI/CD Pipeline (GitHub Actions)

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'

      - name: Install Composer dependencies
        working-directory: ./backend
        run: composer install --prefer-dist --no-dev --optimize-autoloader

      - name: Run tests
        working-directory: ./backend
        run: php artisan test

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '20'

      - name: Build Admin Dashboard
        working-directory: ./frontend/admin-dashboard
        run: |
          npm ci
          npm run build

      - name: Build Parent App
        working-directory: ./frontend/parent-app
        run: |
          npm ci
          npm run build

      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /var/www/snms
            git pull origin main
            cd backend
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            cd ../frontend/admin-dashboard
            npm ci && npm run build
            cd ../parent-app
            npm ci && npm run build
            sudo supervisorctl restart snms-worker:*
            sudo systemctl reload nginx
```

---

## 12. Performance Optimization

### 12.1 PHP-FPM Tuning

Edit `/etc/php/8.3/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

### 12.2 MySQL Optimization

Edit `/etc/mysql/my.cnf`:

```ini
[mysqld]
innodb_buffer_pool_size = 2G
innodb_log_file_size = 256M
max_connections = 200
query_cache_size = 128M
query_cache_type = 1
```

### 12.3 Redis Configuration

Edit `/etc/redis/redis.conf`:

```
maxmemory 1gb
maxmemory-policy allkeys-lru
```

---

## 13. Security Checklist

- [ ] All .env files properly configured
- [ ] SSL certificates installed and auto-renewing
- [ ] Firewall configured (UFW or iptables)
- [ ] SSH key-based authentication only
- [ ] Database accessible only from localhost
- [ ] Redis password protected
- [ ] File permissions properly set (755 for directories, 644 for files)
- [ ] Storage directories writable (775)
- [ ] Debug mode disabled in production
- [ ] CORS properly configured
- [ ] Rate limiting enabled
- [ ] Security headers configured in Nginx
- [ ] Regular backups scheduled
- [ ] Monitoring and alerting set up

---

## 14. Post-Deployment Checklist

- [ ] Verify all API endpoints are accessible
- [ ] Test authentication flow
- [ ] Verify file uploads work (S3/DO Spaces)
- [ ] Test email delivery
- [ ] Test WhatsApp notifications
- [ ] Test payment gateway integration
- [ ] Verify queue workers are running
- [ ] Verify scheduler is executing tasks
- [ ] Check error logs for any issues
- [ ] Verify backup process is working
- [ ] Load test the application
- [ ] Set up monitoring alerts
- [ ] Document server access credentials securely

---

**Estimated Deployment Time**: 4-6 hours (first time)
**Estimated Update Deployment Time**: 10-15 minutes (with CI/CD)
