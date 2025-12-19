---

# 🏪 Aplikasi POS Konsinyasi UMKM (Web Apps)

Aplikasi manajemen point-of-sales sederhana yang dirancang khusus untuk toko retail yang menerima titipan barang (konsinyasi) dari berbagai UMKM. Aplikasi ini fokus pada pencatatan **Stok Masuk (Pagi)** dan **Stok Keluar (Malam)**, serta perhitungan otomatis keuntungan dan setoran mitra.

---

## 🚀 Tech Stack

Project ini dibangun menggunakan arsitektur **Monolith Modern** dengan teknologi berikut:

*   **Backend Framework:** Laravel 12
*   **Frontend Framework:** Vue.js 3 (via Inertia.js)
*   **Admin Panel:** FilamentPHP v3 (Super Admin Dashboard)
*   **Audit Trail:** Spatie Activitylog
*   **Styling:** Tailwind CSS
*   **Database:** MySQL (Eloquent ORM)
*   **Authentication:** Laravel Breeze (Customized)

---

## 👥 Tim Pengembang & Pembagian Tugas

| Nama | Role | Fokus Pengerjaan |
| --- | --- | --- |
| 👨‍💻 **Belva** | **Lead / Backend 1** | Setup Project, Database Schema, Filament Panel (Super Admin), Git Repository Manager. |
| 👨‍💻 **Rivaldi** | **Backend 2** | Logic Controller (`PosController`), Routing, Kalkulasi Markup & Profit, Validasi Data. |
| 👩‍💻 **Nurita** | **Frontend 1** | UI/UX Design, Atomic Components (`Card`, `Button`), Styling Tailwind, Mobile Responsiveness. |
| 👨‍💻 **Amar** | **Frontend 2** | Page Integration (`OpenShop`, `CloseShop`), Form Handling (`useForm`), API Integration via Inertia. |

---

## 🔒 Keamanan & Audit Trail (Privacy First)

Sistem ini menerapkan standar keamanan ketat untuk integritas data:

1.  **Role-Based Access Control (RBAC):**
    *   **Super Admin:** Akses penuh ke Panel Filament (`/admin`) untuk manajemen User, Mitra, dan Laporan.
    *   **Employee:** Akses khusus ke POS Dashboard (`/dashboard`) untuk input transaksi harian.
    *   *Note:* Registrasi publik dinonaktifkan. Akun baru hanya bisa dibuat oleh Super Admin.
2.  **Audit Trail (Log Aktivitas):**
    *   Mencatat setiap perubahan data (Create, Update, Delete) pada User, Partner, dan Transaksi.
    *   Mencatat riwayat Login pengguna.
    *   Log bersifat **Read-Only** dan bisa dipantau via menu "Audit Logs" di Panel Admin.

---

## 🛠️ Cara Install & Setup (Local Development)

Ikuti langkah-langkah ini untuk menjalankan project di komputer Anda.

### 1. Prasyarat

Pastikan sudah terinstall:

*   PHP >= 8.4
*   Composer
*   Node.js & NPM
*   MySQL (XAMPP/Laragon)

### 2. Clone Repository

```bash
git clone https://github.com/belpythons/posita.git
cd posita

```

### 3. Install Dependencies

Install library PHP dan JavaScript.

```bash
composer install
npm install
```

### 4. Setup Environment

Duplikat file `.env.example` dan ubah menjadi `.env`.

```bash
cp .env.example .env
```

Buka file `.env` dan sesuaikan koneksi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=retailer  # Pastikan buat database ini di phpMyAdmin
DB_USERNAME=root
DB_PASSWORD=

```

### 5. Generate Key & Migrasi

Penting: Migrasi juga akan membuat tabel untuk `activity_log`.

```bash
php artisan key:generate
php artisan migrate
```

### 6. Buat Akun Super Admin

Jalankan perintah ini untuk membuat user yang bisa akses panel `/admin`.

```bash
php artisan make:filament-user
# Ikuti instruksi di terminal (Nama, Email, Password)
```

### 7. Jalankan Aplikasi

Buka dua terminal terpisah:

**Terminal 1 (Backend Server):**

```bash
php artisan serve
```

**Terminal 2 (Frontend Compiler):**

```bash
npm run dev
```

Akses Aplikasi:
*   **Login:** `http://localhost:8000/login`
    *   *Super Admin* -> Redirect ke `/admin`
    *   *Employee* -> Redirect ke `/dashboard`

---

## 📂 Struktur Folder Penting (Zona Kerja Tim)

Agar tidak terjadi *conflict* saat push code, perhatikan area kerja masing-masing:

*   **Belva (Core & Admin):**
    *   `app/Models/*`
    *   `database/migrations/*`
    *   `app/Filament/*`
*   **Rivaldi (Logic):**
    *   `app/Http/Controllers/PosController.php`
    *   `routes/web.php`
*   **Nurita (UI Components):**
    *   `resources/js/Components/ConsignmentCard.vue`
    *   `resources/js/Layouts/EmployeeLayout.vue`
*   **Amar (Pages):**
    *   `resources/js/Pages/Pos/OpenShop.vue`
    *   `resources/js/Pages/Pos/CloseShop.vue`

---

## 📝 Alur Penggunaan Aplikasi (User Flow)

1.  **Setup Awal (Super Admin):**
    *   Login ke `/admin`.
    *   Menu **Users**: Buat akun untuk Karyawan (Role: Employee).
    *   Menu **Partners**: Tambah data Mitra tetap.
2.  **Buka Kedai - Input Pagi (Karyawan):**
    *   Login menggunakan akun Employee.
    *   Pilih menu **"Buka Kedai"**.
    *   Pilih Mitra & Input Barang (Nama, Stok, Modal).
    *   Sistem menghitung Harga Jual otomatis.
3.  **Tutup Kedai - Input Malam (Karyawan):**
    *   Pilih menu **"Tutup Kedai"**.
    *   Input **Sisa Stok (Fisik)**.
    *   Simpan. Sistem menghitung Profit & Revenue.
4.  **Monitoring (Super Admin):**
    *   Cek Dashboard `/admin` untuk laporan keuangan.
    *   Cek menu **Audit Logs** untuk memantau aktivitas karyawan.

---

## ⚠️ Catatan Penting

*   **Filament Version:** Project ini menggunakan Filament **v3.2**.
*   **Laravel Version:** Laravel 12.
*   **Security:** Password user baru di-hash otomatis. Reset password bisa dilakukan oleh Admin.

---

*Dibuat untuk tugas Project Development Tim.*
