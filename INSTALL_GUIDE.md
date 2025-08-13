# 🚀 Panduan Instalasi LMS-MOOC untuk Publisher

## 📋 Tentang Aplikasi

**LMS-MOOC** adalah sistem pembelajaran online (Learning Management System) untuk PPSDM yang memiliki fitur:
- Manajemen materi pembelajaran (upload PDF)
- Bank soal dengan pilihan ganda
- Manajemen kategori pembelajaran
- Dashboard admin dan user
- Export data ke Excel

## 💻 Kebutuhan Server

### Minimal Server Requirements:
- **Server**: Linux Ubuntu 20.04+ atau Windows Server 2019+
- **RAM**: 2GB (disarankan 4GB)
- **Storage**: 10GB (disarankan 20GB SSD)
- **PHP**: 8.2 atau lebih baru
- **Database**: MySQL 8.0 atau MariaDB 10.6+
- **Web Server**: Apache atau Nginx

### Software yang Dibutuhkan:
1. **PHP 8.2+** dengan extensions:
   - php-fpm, php-mysql, php-mbstring, php-xml, php-curl
   - php-zip, php-gd, php-intl, php-bcmath
2. **Composer** (PHP package manager)
3. **Node.js 18+** dan NPM
4. **MySQL/MariaDB** database server
5. **Apache/Nginx** web server

## 📦 File yang Disertakan

Dalam source code ini terdapat:
```
LMS-MOOC/
├── app/                    # Core aplikasi Laravel
├── database/               # Database migrations & seeders
├── public/                 # Web accessible files
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── storage/                # File uploads & logs
├── composer.json           # PHP dependencies
├── package.json            # Node.js dependencies
├── .env.example            # Environment configuration template
└── artisan                 # Laravel command line tool
```

## 🔧 Langkah Instalasi Cepat

### 1. Persiapan Server
```bash
# Update sistem (Ubuntu/Debian)
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 dan extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl \
php8.2-bcmath php8.2-cli

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MySQL
sudo apt install mysql-server
```

### 2. Setup Database
```bash
# Masuk ke MySQL
sudo mysql -u root -p

# Buat database dan user
CREATE DATABASE lms_mooc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lms_user'@'localhost' IDENTIFIED BY 'password_yang_kuat';
GRANT ALL PRIVILEGES ON lms_mooc_db.* TO 'lms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Deploy Aplikasi
```bash
# Upload source code ke server (misal ke /var/www/lms-mooc)
cd /var/www/lms-mooc

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit file .env dengan editor (nano/vim)
nano .env
```

### 4. Konfigurasi Environment (.env)
```env
APP_NAME="LMS-MOOC PPSDM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_mooc_db
DB_USERNAME=lms_user
DB_PASSWORD=password_yang_kuat

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 5. Setup Database dan Permissions
```bash
# Jalankan migrasi database
php artisan migrate

# Setup admin user default
php artisan db:seed

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Create storage link
php artisan storage:link

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Konfigurasi Web Server

#### Apache Configuration
Buat file `/etc/apache2/sites-available/lms-mooc.conf`:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/lms-mooc/public
    
    <Directory /var/www/lms-mooc/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/lms-mooc_error.log
    CustomLog ${APACHE_LOG_DIR}/lms-mooc_access.log combined
</VirtualHost>
```

```bash
# Enable site
sudo a2ensite lms-mooc.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

#### Nginx Configuration
Buat file `/etc/nginx/sites-available/lms-mooc`:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/lms-mooc/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/lms-mooc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔑 Login Information

### Default Admin Account:
- **URL**: `https://your-domain.com/login`
- **Email**: `superadmin@elearning.kemenlh.go.id`
- **Password**: `kemenlh.go.id`

⚠️ **PENTING**: Segera ganti password default setelah login pertama!

## 🔒 Security Setup

### 1. SSL Certificate (Recommended)
```bash
# Install Certbot untuk Let's Encrypt
sudo apt install certbot python3-certbot-apache  # untuk Apache
# atau
sudo apt install certbot python3-certbot-nginx   # untuk Nginx

# Generate SSL certificate
sudo certbot --apache -d your-domain.com        # untuk Apache
# atau
sudo certbot --nginx -d your-domain.com         # untuk Nginx
```

### 2. Firewall Setup
```bash
# Setup UFW firewall
sudo ufw allow ssh
sudo ufw allow 'Apache Full'  # atau 'Nginx Full'
sudo ufw enable
```

### 3. Regular Backup
```bash
# Backup database (jalankan secara berkala)
mysqldump -u lms_user -p lms_mooc_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup uploaded files
tar -czf uploads_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/lms-mooc/storage/app/public/
```

## 📊 Monitoring & Maintenance

### Log Files
- **Application logs**: `/var/www/lms-mooc/storage/logs/laravel.log`
- **Web server logs**: `/var/log/apache2/` atau `/var/log/nginx/`
- **PHP logs**: `/var/log/php8.2-fpm.log`

### Regular Maintenance
```bash
# Clear application cache (jika ada masalah)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Update dependencies (lakukan dengan hati-hati)
composer update
npm update && npm run build
```

## 🆘 Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions: `sudo chown -R www-data:www-data storage bootstrap/cache`
   - Check logs: `tail -f storage/logs/laravel.log`

2. **Database Connection Error**
   - Verify database credentials di file `.env`
   - Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

3. **Assets Not Loading**
   - Run: `npm run build && php artisan storage:link`
   - Check web server configuration

4. **Upload Files Not Working**
   - Check storage permissions: `chmod -R 775 storage`
   - Verify `upload_max_filesize` di PHP configuration

## 📞 Support

Jika mengalami masalah selama instalasi:
1. Cek log files untuk error messages
2. Pastikan semua requirements terpenuhi
3. Verify file permissions dan ownership
4. Test database connection terlebih dahulu

---

**Catatan**: Ganti `your-domain.com` dengan domain sebenarnya dan `password_yang_kuat` dengan password yang aman.

**Estimasi waktu instalasi**: 30-60 menit (tergantung pengalaman server administration)
