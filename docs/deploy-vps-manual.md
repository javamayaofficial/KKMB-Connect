# Deploy Manual ke VPS via SSH

Panduan deploy KKMB Connect langsung ke **VPS** (Ubuntu 22.04/24.04) menggunakan Nginx + PHP-FPM + MySQL.

## 1. Masuk ke Server
```bash
ssh root@IP_SERVER
```

## 2. Install Paket Dasar
```bash
apt update && apt upgrade -y
apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-tokenizer \
  unzip git curl
```

Install Composer:
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

## 3. Siapkan Database
```bash
mysql -u root -p
```
```sql
CREATE DATABASE kkmb_connect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kkmb_user'@'localhost' IDENTIFIED BY 'password_kuat';
GRANT ALL PRIVILEGES ON kkmb_connect.* TO 'kkmb_user'@'localhost';
FLUSH PRIVILEGES; EXIT;
```

## 4. Clone Repo
```bash
mkdir -p /var/www/kkmb-connect
cd /var/www/kkmb-connect
git clone https://github.com/USERNAME/kkmb-connect.git .
```

## 5. Install & Konfigurasi
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
nano .env   # isi APP_URL, DB_*, APP_ENV=production, APP_DEBUG=false, integrasi
```

## 6. Migrasi, Seed, Storage, Cache
```bash
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

## 7. Permission
```bash
chown -R www-data:www-data /var/www/kkmb-connect
chmod -R 775 /var/www/kkmb-connect/storage /var/www/kkmb-connect/bootstrap/cache
```

## 8. Konfigurasi Nginx
Buat `/etc/nginx/sites-available/kkmb-connect`:
```nginx
server {
    listen 80;
    server_name kkmbconnect.id www.kkmbconnect.id;
    root /var/www/kkmb-connect/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```
Aktifkan & reload:
```bash
ln -s /etc/nginx/sites-available/kkmb-connect /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

## 9. Queue Worker via Supervisor
```bash
apt install -y supervisor
nano /etc/supervisor/conf.d/kkmb-worker.conf
```
```ini
[program:kkmb-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/kkmb-connect/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/kkmb-connect/storage/logs/worker.log
```
```bash
supervisorctl reread && supervisorctl update && supervisorctl start kkmb-worker:*
```

## 10. Scheduler via Cron
```bash
crontab -e
```
```
* * * * * php /var/www/kkmb-connect/artisan schedule:run >> /dev/null 2>&1
```

## 11. SSL (Let's Encrypt)
```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d kkmbconnect.id -d www.kkmbconnect.id
```

## Update / Restart Service
```bash
cd /var/www/kkmb-connect
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache
supervisorctl restart kkmb-worker:*
systemctl reload nginx
```

---
Dibangun oleh **Java Maya Studio** — https://wa.me/6285722224391
