# 🚗 Trator - Car Rental Management System

**Sistem Informasi Manajemen Rental Mobil**  
Tugas Perkuliahan Sistem Informasi Manajemen - Kelompok 3

## 📋 Deskripsi Project

Trator adalah sistem manajemen rental mobil berbasis web yang memungkinkan pengelolaan kendaraan, pengguna, dan feedback pelanggan. Sistem ini dilengkapi dengan panel admin yang komprehensif dan integrasi WhatsApp untuk komunikasi langsung dengan pelanggan.

## ✨ Fitur Utama

### 🎯 Frontend (Customer)
- **Home Page**: Landing page dengan informasi perusahaan
- **About**: Informasi tentang perusahaan rental
- **Vehicles**: Katalog kendaraan dengan status real-time
- **Feedbacks**: Testimoni pelanggan dengan sistem carousel
- **Contact**: Form kontak terintegrasi WhatsApp
- **Authentication**: Login/Register untuk pelanggan

### 🛠️ Admin Panel
- **Dashboard**: Statistik dan grafik interaktif (Chart.js)
- **Vehicle Management**: CRUD kendaraan dengan upload foto
- **User Management**: Kelola pengguna dan role
- **Feedback Management**: Moderasi feedback pelanggan
- **Role-based Access**: Sistem otorisasi admin

### 📱 Integrasi WhatsApp
- **Booking System**: Booking langsung via WhatsApp
- **Contact Form**: Pesan otomatis ke admin
- **Template Messages**: Format pesan terstruktur

## 🏗️ Struktur Database

```sql
-- Users Table
users (id, username, password, role, created_at)

-- Cars Table  
cars (id, brand, model, year, price_per_day, status, image, created_at)

-- Feedback Table
feedback (id, name, message, created_at)
```

## 🎨 Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 4
- **Charts**: Chart.js
- **Authentication**: PHP Sessions
- **File Upload**: PHP native
- **Routing**: Custom PHP routing system

## 📁 Struktur Project

```
trator/
├── database/
│   └── trator_complete.sql
├── includes/
│   ├── functions.php
│   ├── auth.php
│   └── admin_auth.php
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   ├── templates/
│   │   ├── header.php
│   │   ├── navbar.php
│   │   └── footer.php
│   ├── pages/
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── vehicles.php
│   │   │   ├── users.php
│   │   │   ├── feedback.php
│   │   │   └── login.php
│   │   ├── home.php
│   │   ├── about.php
│   │   ├── vehicles.php
│   │   ├── feedbacks.php
│   │   ├── contact.php
│   │   └── auth.php
│   └── index.php
└── README.md
```

## 🚀 Instalasi & Setup

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/trator-rentalMobil-SIM-KELOMPOK3.git
   cd trator
   ```

2. **Setup Database**
   - Import `database/trator.sql` ke MySQL
   - Update konfigurasi database di `config/database.php`

3. **Konfigurasi Web Server**
   - Pastikan PHP dan MySQL aktif

4. **Setup WhatsApp**
   - Update nomor WhatsApp di file contact dan vehicles
   - Format: `6285704866825`

## 👥 Akun Default

**Admin:**
- Username: `admin`
- Password: `admin123`
- Role: `admin`

**User:**
- Username: `user`
- Password: `user123`
- Role: `user`

## 🎯 Fitur Unggulan

### 📊 Dashboard Analytics
- Grafik status kendaraan (Doughnut Chart)
- Trend feedback bulanan (Line Chart)
- Top brand kendaraan (Bar Chart)
- Quick stats dengan progress bars

### 🚗 Vehicle Management
- Upload foto kendaraan
- Status tracking (Available/Rented/Maintenance)
- CRUD operations lengkap
- Filter dan pencarian

### 💬 Communication System
- WhatsApp integration
- Template pesan otomatis
- Feedback system dengan rating
- Contact form validation

## 🔐 Security Features

- Password hashing (PHP password_hash)
- SQL injection protection (PDO prepared statements)
- XSS protection (htmlspecialchars)
- Session management
- Role-based access control
- File upload validation

## 📱 Responsive Design

- Mobile-first approach
- Bootstrap 4 grid system
- Touch-friendly interface
- Cross-browser compatibility

## 👨‍💻 Tim Pengembang

**Kelompok 3 - Sistem Informasi Manajemen**

- **Abib** - Backend Developer
- **Ashil** - Frontend Developer  
- **Ivan** - Database Designer
- **Fahmi** - UI/UX Designer
- **Firman** - System Analyst
- **Mas Gusti** - Project Manager

## 📞 Kontak

- **WhatsApp**: +62 857-0486-6825

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik - Tugas Perkuliahan SIM.

---

**© 2024 Trator Car Rental - Kelompok 3 SIM**
