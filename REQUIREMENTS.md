# LMS-MOOC - System Requirements & Installation Guide

## 📋 Project Overview

**LMS-MOOC** adalah sistem Learning Management System (LMS) berbasis web yang dikembangkan menggunakan Laravel framework. Aplikasi ini dirancang khusus untuk PPSDM (Pusat Pengembangan Sumber Daya Manusia) dan mendukung fitur-fitur e-learning seperti manajemen materi pembelajaran, bank soal, dan kategori pembelajaran.

## 🎯 Key Features

- **User Authentication & Authorization**
  - Admin dan User management
  - Role-based access control
  - Email verification

- **Material Management**
  - Upload dan manajemen file PDF
  - Kategorisasi materi pembelajaran
  - File streaming dan download

- **Question Bank System**
  - Multiple choice questions
  - Question categorization
  - Export functionality (Excel)
  - Bulk question import

- **Category Management**
  - Dynamic category creation
  - Material grouping
  - Category-based filtering

- **API Integration**
  - RESTful API endpoints
  - Webhook support untuk integrasi eksternal
  - Rate limiting dan throttling

## 🖥️ System Requirements

### Server Requirements

#### Minimum Requirements
- **Operating System**: Linux (Ubuntu 20.04+), Windows Server 2019+, atau macOS 12+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **PHP**: PHP 8.2 atau yang lebih baru
- **Database**: MySQL 8.0+ atau MariaDB 10.6+
- **Memory**: 2GB RAM minimum (4GB recommended)
- **Storage**: 10GB disk space minimum
- **Node.js**: 18.x atau yang lebih baru (untuk build assets)

#### Recommended Requirements
- **Memory**: 4GB+ RAM
- **Storage**: 20GB+ SSD storage
- **CPU**: 2+ cores
- **Network**: Stable internet connection untuk package downloads

### PHP Extensions Required

```bash
# Core Extensions
php-fpm
php-cli
php-mysql
php-pdo
php-mbstring
php-xml
php-curl
php-zip
php-gd
php-intl
php-bcmath
php-soap
php-json
php-tokenizer
php-openssl
php-fileinfo
```

### Database Requirements

- **MySQL 8.0+** atau **MariaDB 10.6+**
- Database user dengan privileges:
  - CREATE, ALTER, DROP (untuk migrations)
  - SELECT, INSERT, UPDATE, DELETE (untuk operations)
  - INDEX (untuk performance optimization)

## 📦 Dependencies

### PHP Dependencies (Composer)

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "phpoffice/phpspreadsheet": "1.29"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pail": "^1.2.2",
    "laravel/pint": "^1.13",
    "laravel/sail": "^1.41",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "phpunit/phpunit": "^11.5.3"
  }
}
```

### Node.js Dependencies (NPM/Yarn)

```json
{
  "devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "axios": "^1.8.2",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^1.2.0",
    "tailwindcss": "^4.0.0",
    "vite": "^6.2.4"
  }
}
```

## 🚀 Installation Guide

### 1. Environment Setup

#### Clone Repository
```bash
git clone [repository-url]
cd LMS-MOOC
```

#### Install PHP Dependencies
```bash
composer install
```

#### Install Node.js Dependencies
```bash
npm install
# atau
yarn install
```

### 2. Configuration

#### Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:

```env
# Application Settings
APP_NAME="LMS-MOOC PPSDM"
APP_ENV=production
APP_KEY=[generated-key]
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=generate_lms
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache Configuration
CACHE_STORE=database

# Queue Configuration
QUEUE_CONNECTION=database

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Database Setup

#### Create Database
```sql
CREATE DATABASE lgenerate_lms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Run Migrations
```bash
php artisan migrate
```

#### Seed Database
```bash
php artisan db:seed
```

**Default Admin Account:**
- Email: `superadmin@elearning.kemenlh.go.id`
- Password: `kemenlh.go.id`

### 4. File Permissions

#### Set Proper Permissions (Linux/macOS)
```bash
# Storage dan cache directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (adjust www-data sesuai web server user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

#### Create Storage Symlink
```bash
php artisan storage:link
```

### 5. Build Assets

#### Development
```bash
npm run dev
```

#### Production
```bash
npm run build
```

### 6. Web Server Configuration

#### Apache Virtual Host Example
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/LMS-MOOC/public
    
    <Directory /path/to/LMS-MOOC/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/lms-mooc_error.log
    CustomLog ${APACHE_LOG_DIR}/lms-mooc_access.log combined
</VirtualHost>
```

#### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/LMS-MOOC/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

## 🔧 Configuration Options

### Cache Configuration
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue Workers (Optional)
```bash
# Start queue worker
php artisan queue:work

# Or use supervisor for production
```

### Scheduled Tasks (Optional)
Add to crontab:
```cron
* * * * * cd /path/to/LMS-MOOC && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Database Schema

### Main Tables

1. **users** - User management dan authentication
2. **materials** - Learning materials (PDF files)
3. **questions** - Question bank dengan multiple choice
4. **categories** - Material categorization
5. **sessions** - User session management
6. **cache** - Application caching
7. **jobs** - Background job processing

### Key Relationships
- Materials ↔ Categories (Many-to-One)
- Materials ↔ Questions (One-to-Many)
- Categories ↔ Materials (One-to-Many)

## 🔐 Security Considerations

### Production Security
- Set `APP_DEBUG=false` di production
- Use HTTPS dengan SSL certificate
- Regular database backups
- Keep Laravel dan dependencies updated
- Implement proper firewall rules
- Monitor logs untuk suspicious activities

### File Upload Security
- Validate file types (PDF only untuk materials)
- Set maximum file size limits
- Store uploaded files outside web root
- Implement virus scanning (recommended)

## 🚦 Testing

### Run Tests
```bash
# Unit tests
php artisan test

# Feature tests
php artisan test --filter=Feature

# With coverage
php artisan test --coverage
```

## 📈 Performance Optimization

### Recommended Optimizations
1. **Enable OPcache** untuk PHP
2. **Use Redis** untuk session dan cache (optional)
3. **Implement CDN** untuk static assets
4. **Database indexing** pada frequently queried columns
5. **Enable Gzip compression** di web server
6. **Regular database optimization**

### Monitoring
- Monitor disk space (terutama untuk uploaded files)
- Database performance monitoring
- Application logs monitoring
- Server resource utilization

## 🆘 Troubleshooting

### Common Issues

#### 1. Permission Denied Errors
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 2. Database Connection Issues
- Verify database credentials di `.env`
- Check database server status
- Ensure database exists dan user has proper privileges

#### 3. Asset Loading Issues
```bash
npm run build
php artisan storage:link
```

#### 4. Session Issues
```bash
php artisan session:table
php artisan migrate
```
### Version Information
- **Laravel Version**: 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+
- **Node.js**: 18.x+

---

**Note**: Pastikan untuk mengganti semua placeholder values (seperti domain, email, passwords) dengan values yang sesuai untuk environment production Anda.
