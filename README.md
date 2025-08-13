# 🎓 LMS-MOOC - Learning Management System

<div align="center">

<img src="public/images/kemenlh-logo.png" alt="KemenLH Logo" width="100" height="auto">

**Sistema Manajemen Pembelajaran untuk PPSDM (Pusat Pengembangan Sumber Daya Manusia)**

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![Database](https://img.shields.io/badge/Database-MySQL-orange.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

</div>

## 📝 Tentang Aplikasi

**LMS-MOOC** adalah sistem Learning Management System (LMS) yang dikembangkan khusus untuk mendukung program e-learning di lingkungan PPSDM. Aplikasi ini menyediakan platform lengkap untuk manajemen materi pembelajaran, bank soal, dan administrasi pengguna.

## 🎯 Fitur Utama

### 📚 Manajemen Materi Pembelajaran

- Upload dan organize file PDF
- Kategorisasi materi
- System pencarian dan filtering
- File streaming dan download

### ❓ Bank Soal Digital

- Multiple choice questions
- Import/export soal dari Excel
- Kategorisasi berdasarkan materi
- Tingkat kesulitan soal

### 👥 User Management

- Role-based access (Admin/User)
- Authentication dan authorization
- Profile management

### 📊 Export & Reporting

- Export soal ke format Excel
- Analytics dan reporting
- Data backup

### 🔌 API Integration

- RESTful API endpoints
- Webhook support
- Third-party integration ready

## 🛠️ Teknologi yang Digunakan

| Kategori            | Teknologi                         |
| ------------------- | --------------------------------- |
| **Backend**         | Laravel 12.x (PHP 8.2+)           |
| **Frontend**        | Blade Templates + TailwindCSS 4.0 |
| **Database**        | MySQL 8.0+                        |
| **Build Tools**     | Vite 6.x                          |
| **File Processing** | PhpSpreadsheet                    |
| **Authentication**  | Laravel Auth                      |

## 🚀 Quick Start

### Untuk Publisher/Deployment

Jika Anda ingin mempublish aplikasi ini di server production, silakan ikuti panduan berikut:

1. **📋 [REQUIREMENTS.md](REQUIREMENTS.md)** - System requirements lengkap dan technical specifications
2. **⚡ [INSTALL_GUIDE.md](INSTALL_GUIDE.md)** - Panduan instalasi step-by-step yang mudah diikuti
3. **✅ [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)** - Checklist deployment untuk memastikan semua berjalan dengan baik

### Untuk Development

```bash
# Clone repository
git clone [repository-url]
cd LMS-MOOC

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (sesuaikan .env terlebih dahulu)
php artisan migrate
php artisan db:seed

# Build assets
npm run dev

# Start development server
php artisan serve
```

### Default Login

Setelah installation dan seeding:

- **URL**: `http://localhost:8000/login`
- **Email**: `superadmin@elearning.kemenlh.go.id`
- **Password**: `kemenlh.go.id`

⚠️ **PENTING**: Ganti password default setelah login pertama!

## 📁 Struktur Aplikasi

```
LMS-MOOC/
├── app/
│   ├── Http/Controllers/    # Controllers untuk web dan API
│   ├── Models/             # Eloquent models (User, Material, Question, Category)
│   └── Exports/            # Export classes untuk Excel
├── database/
│   ├── migrations/         # Database schema migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── storage/
│   ├── app/public/        # File uploads
│   └── logs/              # Application logs
└── public/                # Web accessible files
```

## 🗃️ Database Schema

### Tabel Utama

- **users** - User management dengan role admin/user
- **materials** - Materi pembelajaran (file PDF)
- **questions** - Bank soal multiple choice
- **categories** - Kategori untuk mengorganisir materi

## 📊 API Endpoints

### Materials API

```
GET    /api/materials           # List all materials
POST   /api/materials           # Create new material
GET    /api/materials/{id}      # Get specific material
PUT    /api/materials/{id}      # Update material
DELETE /api/materials/{id}      # Delete material
```

### Questions API

```
GET    /api/questions                    # List all questions
POST   /api/questions                    # Create new question
GET    /api/questions/{id}               # Get specific question
PUT    /api/questions/{id}               # Update question
DELETE /api/questions/{id}               # Delete question
GET    /api/questions/material/{id}      # Get questions by material ID
```

### N8N Webhook Integration

```
POST   /api/questions/webhook            # Bulk create questions via n8n
POST   /api/questions/clear-cache/{id}   # Clear completion cache for material
```

**Webhook Format:**

```json
{
  "material_id": 38,
  "questions": [
    {
      "question": "Pertanyaan soal?",
      "options": {
        "A": "Pilihan A",
        "B": "Pilihan B",
        "C": "Pilihan C",
        "D": "Pilihan D",
        "E": "Pilihan E (optional)"
      },
      "answer": "A",
      "explanation": "Penjelasan jawaban (optional)",
      "difficulty": "medium"
    }
  ]
}
```

Rate limiting: 60 requests per minute

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=Feature
php artisan test --filter=Unit

# Run with coverage
php artisan test --coverage
```

## 📈 Performance Optimization

### Recommended Production Settings

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Enable OPcache in PHP
opcache.enable=1
opcache.memory_consumption=128
```

## 🔧 Configuration

### Environment Variables

Key environment variables yang perlu dikonfigurasi:

```env
# Application
APP_NAME="LMS-MOOC PPSDM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_mooc_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# File Upload
UPLOAD_MAX_SIZE=10240  # 10MB in KB
```

## 🐛 Troubleshooting

### Common Issues

1. **500 Internal Server Error**

   - Check storage permissions: `chmod -R 775 storage bootstrap/cache`
   - Verify `.env` configuration

2. **Database Connection Error**

   - Verify database credentials
   - Ensure database server is running

3. **File Upload Issues**
   - Check `upload_max_filesize` in PHP config
   - Verify storage directory permissions

### Log Files

- Application logs: `storage/logs/laravel.log`
- Web server logs: Check Apache/Nginx error logs

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team & Support

- **Developed for**: PPSDM (Pusat Pengembangan Sumber Daya Manusia)
- **Technology Stack**: Laravel, MySQL, TailwindCSS
