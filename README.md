<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# 🏥 Surgical Management System

A Laravel-based web application for managing surgeries, doctors, payments, and insurance within clinical or hospital environments.

This project includes:

- ✅ Surgery scheduling
- 👨‍⚕️ Doctor and specialist management
- 💳 Invoice and payment workflows
- 🔐 Role-based access control
- 📋 Insurance tracking (basic & supplementary)
- 🕓 Activity logging and user tracking

---

## 📦 Tech Stack

- PHP (Laravel Framework)
- MySQL
- Docker & Docker Compose
- Tailwind CSS (via Laravel Mix)
- Redis (optional for queue)
- Laravel Breeze (authentication scaffolding)

---

## 🚀 Getting Started (via Docker)

Follow these steps to get the system running locally.

### 1. Clone the repository

```bash
git clone https://github.com/Erfan-Mohamadi/surgical-management-system.git
cd surgical-management-system
```

### 2. Copy environment file

```bash
cp .env.example .env
```

Edit `.env` and set your DB credentials, mail settings, etc.

### 3. Build & run Docker containers

```bash
docker-compose up -d --build
```

### 4. Install backend & frontend dependencies

```bash
docker-compose exec app composer install
docker-compose exec app npm install && npm run dev
```

### 5. Generate application key

```bash
docker-compose exec app php artisan key:generate
```

### 6. Run database migrations and seed admin user

```bash
docker-compose exec app php artisan migrate --seed
```

Admin credentials (defined in `UserSeeder.php`):

```
mobile:   0999999999
Email:    admin@test.com  
Password: password
```

---

## ⚙️ Additional Setup Steps

### 7. Compile production assets (optional)

```bash
docker-compose exec app npm run build
```

### 8. Create symbolic storage link

```bash
docker-compose exec app php artisan storage:link
```

### 9. Set correct permissions (if needed)

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

---

## ✉️ Mail Configuration (Optional)

To send emails (e.g., password reset, notifications), update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@surgical-system.local
MAIL_FROM_NAME="Surgical System"
```

---

## ⚡ Queue Setup (Optional)

If using queued jobs (e.g., for emails):

```bash
docker-compose exec app php artisan queue:work
```

In production, use [Supervisor](https://laravel.com/docs/queues#supervisor-configuration) to keep queue workers running.

---

## 🧪 Running Tests

```bash
docker-compose exec app php artisan test
```

---

## 📁 Folder Structure Highlights

| Folder                          | Purpose                            |
|---------------------------------|------------------------------------|
| `app/Http/Controllers/Admin/`   | Admin controllers                  |
| `app/Models/`                   | Eloquent models                    |
| `app/Http/Requests/`            | Form request validation            |
| `database/seeders/`             | Seeder files (e.g., admin user)    |
| `resources/views/`              | Blade templates                    |
| `routes/web.php`                | Web routes                         |

---

## 🔐 Default Admin Login

```text
Email:    admin@test.com
Password: password
```

---

## ✅ Production Recommendations

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Use NGINX + HTTPS (Let's Encrypt)
- Run `npm run build` for optimized frontend assets
- Use Supervisor for queue management
- Regularly back up the database and `.env` file

---

## 🙌 Contributing

Feel free to open an issue or submit a PR!  
Please follow PSR-12 coding standards and test your changes locally before committing.

---

## 📝 License

This project is open-sourced under the [MIT license](LICENSE).
