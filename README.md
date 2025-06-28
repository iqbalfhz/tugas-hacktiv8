# 🎯 Kaido Kit – Laravel Filament Admin Panel Starter

[![Laravel](https://img.shields.io/badge/laravel-12.x-red)](https://laravel.com)
[![Filament](https://img.shields.io/badge/filament-3.x-green)](https://filamentphp.com)
[![Dockerized](https://img.shields.io/badge/docker-ready-blue)](https://www.docker.com/)
[![License: MIT](https://img.shields.io/badge/license-MIT-lightgrey.svg)](LICENSE)

Kaido Kit adalah sebuah **starter kit Laravel 12** yang telah terintegrasi penuh dengan **Filament Admin Panel v3**. Tujuannya adalah mempercepat pengembangan dashboard admin dan backend system dengan fitur modern yang sudah dikonfigurasi sebelumnya.

> 🔗 Resource Resmi: [siubie/kaido-kit di Filament Plugin](https://filamentphp-com.translate.goog/plugins/siubie-kaido-kit?_x_tr_sl=en&_x_tr_tl=id&_x_tr_hl=id&_x_tr_pto=tc)

---

## 📄 Project Overview

Kaido Kit memudahkan kamu membangun sistem backend modern, lengkap dengan autentikasi, manajemen pengguna, export data, dan dokumentasi API otomatis. Dirancang untuk digunakan langsung tanpa perlu banyak setup awal.

---

## 🧰 Technologies Used

- **Laravel 12** – PHP framework modern berbasis MVC
- **PHP 8.2**
- **Filament v3** – UI Admin Panel dengan TailwindCSS
- **MySQL / Docker**
- **Sanctum** – Token-based Authentication
- **Socialite (Google)** – Login dengan akun Google
- **Filament Shield** – Manajemen Role & Permission
- **Filament Breezy** – Autentikasi lengkap
- **Filament Excel** – Export ke Excel
- **Resend** – Pengiriman Email modern
- **Dedoc Scramble** – Dokumentasi API otomatis
- **Laravel Blueprint** – Generasi kode otomatis dari YAML
- **PestPHP & Mockery** – Framework testing modern

---

## ✨ Features

- 🔐 **Login/Register + Google Login**
- 🎛️ **Admin Panel dengan Filament**
- 🛡️ **Manajemen Role & Permission**
- 👤 **User Impersonation (Login sebagai user lain)**
- 📤 **Export Data ke Excel**
- 🌐 **Token Authentication (API Ready)**
- 📜 **Auto API Documentation (Dedoc Scramble)**
- 📨 **Email Notifikasi via Resend**
- 🧹 **Setup Cepat: `composer run-script setup`**

---

## 🤖 AI Support Explanation

Proyek ini mengintegrasikan dan terinspirasi oleh AI-support tools untuk otomatisasi dan efisiensi:

| Tool / AI | Penjelasan |
|-----------|------------|
| 🧠 **IBM Granite** | Digunakan untuk menyusun dokumentasi otomatis dan rekomendasi struktur kode secara natural |
| 🧾 **Dedoc Scramble** | Menghasilkan dokumentasi API secara otomatis dari kode controller |
| 📐 **Laravel Blueprint** | Menyusun model, controller, migration, dan test secara otomatis dari desain YAML |

---

## 🖼️ Demo Screenshots (Rekomendasi)

> *Silakan ambil tangkapan layar dari proyek kamu secara lokal dan unggah ke folder `/screenshots` lalu update path-nya di bawah ini.*

- 🟦 Login Page dengan Google
- 🟩 Filament Admin Dashboard
- 🟧 Halaman User & Role Management
- 🟨 Export Excel Feature
- 🟥 Auto-Generated API Docs

---

## 🚀 Installation

```bash
git clone https://github.com/username/kaido-kit-project.git
cd kaido-kit-project

# Install dependency
composer install

# Salin .env dan generate key
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Jalankan server
php artisan serve
