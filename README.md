# E-Commerce Mock Backend API (Laravel)

Repositori ini memuat *source code* untuk ujian/tugas pembuatan backend e-commerce sederhana berbasis REST API menggunakan **Laravel**. Aplikasi ini beroperasi tanpa database relasional melainkan menggunakan **Mock Data berbasis JSON**.

Selain itu, seluruh dokumentasi *endpoints* dapat diakses secara dinamis menggunakan antarmuka interaktif **Swagger UI** (`darkaonline/l5-swagger`).

---

## 🚀 Fitur Utama
Sistem ini menggunakan penyimpanan lokal (file system) di mana data item disimpan sementara dalam `storage/app/items.json`.
Berikut adalah aksi (CRUD) yang didukung di endpoint `api/items`:

| Method   | Endpoint          | Keterangan                                       |
| -------- | ----------------- | ------------------------------------------------ |
| `GET`    | `/api/items`      | Menampilkan daftar semua barang yang tersedia.   |
| `POST`   | `/api/items`      | Membuat barang baru (membutuhkan `name` & `price`).|
| `GET`    | `/api/items/{id}` | Menampilkan detail barang berdasarkan ID.        |
| `PUT`    | `/api/items/{id}` | Mengubah keseluruhan atribut data sebuah barang.           |
| `PATCH`  | `/api/items/{id}` | Mengupdate salah satu atribut (misal *harga* saja).|
| `DELETE` | `/api/items/{id}` | Menghapus barang berdasarkan ID terkait.         |

> **Catatan Penanganan Error (Error Handling):** 
> - Input divalidasi dengan request validation API *built-in* dari Laravel.
> - Pencarian ID barang yang tidak terdaftar akan mengembalikan status code `404` beserta pesan `"Item dengan ID {id} tidak Ditemukan"`.

---

## 🛠️ Tech Stack & Konfigurasi
- **Framework:** Laravel 11/12 (PHP 8.2+)
- **API Documentation:** [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger) utilizing *PHP 8 OpenApi Attributes*.
- **Database:** Non-RDBMS (Menggunakan format baca-tulis file `items.json`).

---

## 💻 Panduan Instalasi (Setup Project)

Ikuti langkah-langkah di bawah ini untuk memulai menjalankan proyek API Lanjut ini di lokal *(localhost)* Anda.

1. **Clone Repositori:**
   ```bash
   git clone <link_repository_github_anda>
   cd <nama_folder_repository>
   ```

2. **Instalasi Dependencies Composer:**
   ```bash
   composer install
   ```

3. **Buat file `.env` Konfigurasi:**  
   Ambil dari contoh environment Laravel.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Jalankan Instalator API Routing (Opsional Jika Dibutuhkan):**  
   Perintah ini akan memastikan kerangka routing `api` bekerja dengan baik jika belum aktif bawaan framework.
   ```bash
   php artisan install:api
   ```

5. **Generate Dokumentasi Swagger:**  
   Pastikan Anda men-generate ulang swagger file setiap kali ada perombakan route di controller.
   ```bash
   php artisan l5-swagger:generate
   ```

6. **Jalankan Server Lokal (Development):**  
   ```bash
   php artisan serve
   ```
   
> **Testing Dokumentasi Swagger UI:** Buka Browser Anda dan navigasikan menuju > `http://localhost:8000/api/documentation`.

---
