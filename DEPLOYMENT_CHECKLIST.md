# ✅ LMS-MOOC Deployment Checklist

## 📋 Pre-Deployment Checklist

### Server Requirements Verification
- [ ] PHP 8.2+ installed dan configured
- [ ] MySQL/MariaDB 8.0+ installed dan running
- [ ] Composer installed globally
- [ ] Node.js 18+ dan NPM installed
- [ ] Web server (Apache/Nginx) installed dan configured
- [ ] Required PHP extensions installed
- [ ] Server has adequate RAM (2GB minimum, 4GB recommended)
- [ ] Sufficient disk space (10GB minimum, 20GB recommended)

### Security Preparations
- [ ] Firewall configured (ports 22, 80, 443 only)
- [ ] Strong database passwords generated
- [ ] SSL certificate ready (Let's Encrypt recommended)
- [ ] Backup strategy planned
- [ ] Server access credentials secured

## 🚀 Deployment Steps

### 1. Database Setup
- [ ] MySQL/MariaDB server running
- [ ] Database `lms_mooc_db` created
- [ ] Database user created dengan appropriate privileges
- [ ] Database connection tested
- [ ] Character set utf8mb4 configured

### 2. Application Deployment
- [ ] Source code uploaded ke server directory
- [ ] File ownership set ke web server user (www-data)
- [ ] Directory permissions set correctly (755 for directories, 644 for files)
- [ ] Storage dan cache directories writable (775)

### 3. Dependencies Installation
- [ ] `composer install --optimize-autoloader --no-dev` executed
- [ ] `npm install` executed
- [ ] `npm run build` executed successfully
- [ ] All vendor packages installed tanpa errors

### 4. Environment Configuration
- [ ] `.env` file created from `.env.example`
- [ ] `APP_KEY` generated dengan `php artisan key:generate`
- [ ] Database credentials configured di `.env`
- [ ] `APP_ENV` set ke `production`
- [ ] `APP_DEBUG` set ke `false`
- [ ] `APP_URL` set ke correct domain
- [ ] Mail configuration set (optional tapi recommended)

### 5. Database Migration & Seeding
- [ ] `php artisan migrate` executed successfully
- [ ] `php artisan db:seed` executed successfully
- [ ] Default admin user created
- [ ] Database tables created dengan correct structure
- [ ] Sample data loaded (if any)

### 6. Storage & Links
- [ ] `php artisan storage:link` executed
- [ ] Storage symlink created successfully
- [ ] Upload directories writable
- [ ] Log directories writable

### 7. Optimization (Production)
- [ ] `php artisan config:cache` executed
- [ ] `php artisan route:cache` executed
- [ ] `php artisan view:cache` executed
- [ ] OPcache enabled di PHP configuration
- [ ] Composer autoloader optimized

### 8. Web Server Configuration
- [ ] Virtual host/server block configured
- [ ] Document root points ke `public/` directory
- [ ] `.htaccess` atau equivalent Nginx rules working
- [ ] URL rewriting enabled
- [ ] PHP-FPM configured dan running
- [ ] Web server restarted

### 9. SSL & Security
- [ ] SSL certificate installed
- [ ] HTTPS redirection configured
- [ ] Security headers configured
- [ ] File upload limits set appropriately
- [ ] Error pages customized (optional)

## 🧪 Testing Checklist

### Basic Functionality Tests
- [ ] Homepage loads successfully
- [ ] Login page accessible
- [ ] Admin login works dengan default credentials
- [ ] Dashboard loads untuk admin user
- [ ] File upload functionality works
- [ ] Database queries executing properly
- [ ] Session management working
- [ ] Cache system functioning

### Feature Tests
- [ ] Material upload dan management
- [ ] Question creation dan editing
- [ ] Category management
- [ ] User management (if applicable)
- [ ] Export functionality (Excel)
- [ ] API endpoints responding (if used)
- [ ] File download/streaming working

### Performance Tests
- [ ] Page load times acceptable (< 3 seconds)
- [ ] Large file uploads working
- [ ] Multiple concurrent users supported
- [ ] Memory usage within limits
- [ ] Database queries optimized

### Security Tests
- [ ] HTTPS working properly
- [ ] Unauthorized access blocked
- [ ] File upload security working
- [ ] SQL injection protection active
- [ ] XSS protection enabled
- [ ] CSRF protection working

## 🔐 Security Hardening

### Application Security
- [ ] Default admin password changed
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] Error reporting configured appropriately
- [ ] Sensitive data tidak exposed di logs
- [ ] File permissions restrictive
- [ ] Database credentials secured

### Server Security
- [ ] Unnecessary services disabled
- [ ] Regular security updates enabled
- [ ] SSH configured dengan key-based auth (recommended)
- [ ] Fail2ban installed untuk brute force protection
- [ ] Log monitoring configured

## 💾 Backup & Recovery

### Backup Setup
- [ ] Database backup script created
- [ ] File backup strategy implemented
- [ ] Backup storage location secured
- [ ] Automated backup schedule configured
- [ ] Backup restoration procedure tested

### Monitoring Setup
- [ ] Application logs monitored
- [ ] Web server logs monitored
- [ ] Database performance monitored
- [ ] Disk space monitoring configured
- [ ] Uptime monitoring setup (optional)

## 📝 Documentation & Handover

### Client Documentation
- [ ] Admin user credentials provided
- [ ] User manual prepared (if needed)
- [ ] Maintenance instructions provided
- [ ] Backup/restore procedures documented
- [ ] Contact information untuk support

### Technical Documentation
- [ ] Server configuration documented
- [ ] Database schema documented
- [ ] API documentation (if applicable)
- [ ] Troubleshooting guide provided
- [ ] Update procedures documented

## 🎯 Go-Live Checklist

### Final Checks
- [ ] All features tested thoroughly
- [ ] Performance benchmarks met
- [ ] Security audit completed
- [ ] Client approval received
- [ ] DNS records configured (if new domain)
- [ ] Email notifications working (if used)

### Launch Day
- [ ] Maintenance page removed
- [ ] Final backup created
- [ ] Monitoring systems active
- [ ] Support team notified
- [ ] Launch announcement sent (if applicable)

### Post-Launch
- [ ] Monitor system for 24-48 hours
- [ ] Address any immediate issues
- [ ] Performance metrics collected
- [ ] User feedback collected
- [ ] Optimization implemented if needed

## 🚨 Emergency Contacts & Procedures

### Emergency Response
- [ ] Emergency contact list prepared
- [ ] Rollback procedure documented
- [ ] Incident response plan ready
- [ ] Backup admin access configured
- [ ] Emergency maintenance procedures ready

### Key Information
```
Application URL: ___________________
Admin Email: superadmin@elearning.kemenlh.go.id
Admin Password: [CHANGE IMMEDIATELY]
Database Name: lms_mooc_db
Server IP: ___________________
SSH Access: ___________________
```

---

## 📞 Support Information

**Developer/Team**: ___________________
**Support Email**: ___________________
**Emergency Contact**: ___________________
**Documentation**: This file + REQUIREMENTS.md + INSTALL_GUIDE.md

**Deployment Date**: ___________________
**Deployed By**: ___________________
**Client**: PPSDM (Pusat Pengembangan Sumber Daya Manusia)

---

**Note**: Checklist ini harus dilengkapi step by step dan setiap item harus diverifikasi sebelum menandainya sebagai complete. Simpan record deployment ini untuk reference future maintenance dan updates.
