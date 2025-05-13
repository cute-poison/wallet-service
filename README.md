````markdown
# wallet-service

A **Laravel 10** backend API for a fintech wallet application.

---

## 🚀 Features

- **Create wallets** (USD, EUR, etc.)  
- **View balances**  
- **Secure, atomic, idempotent transfers**  
- **Paginated transaction history**  
- **Token authentication** via Laravel Sanctum  
- **Per-user rate limiting**  
- **Interactive Swagger/OpenAPI docs** at `/api/documentation`

---

## 💻 Setup & Installation

1. **Clone the repository**  
   ```bash
   git clone <your-repo-url>
   cd wallet-service
````

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Configure environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   – Edit `.env` to set your database credentials, `APP_URL`, etc.

4. **Prepare the database**

   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Run the application**

   ```bash
   php artisan serve
   ```

   By default this serves at `http://127.0.0.1:8000`

---

## 📄 API Documentation

* **Swagger UI**:

  ```
  http://127.0.0.1:8000/api/documentation
  ```

* **Raw OpenAPI spec**:

  ```
  http://127.0.0.1:8000/api/api-docs.json
  ```

---

## 🧪 Testing

Run the full test suite:

```bash
php artisan test
```

---

## 🛠️ Postman Collection

1. **Import** `storage/api-docs/api-docs.json` into Postman.

2. **Create a Postman Environment** named `wallet-service-local`:

   * `base_url` = `http://127.0.0.1:8000/api`
   * `email`    = `test@example.com`
   * `password` = `password`
   * `token`    = *(populated by login request)*

3. **Login request** to populate `{{token}}`:

   * **POST** `{{base_url}}/login`
   * **Body (raw JSON)**:

     ```json
     {
       "email": "{{email}}",
       "password": "{{password}}"
     }
     ```
   * In the **Tests** tab:

     ```js
     const json = pm.response.json();
     pm.environment.set('token', json.token || json.access_token || '');
     ```

4. **Set Authorization** at the **collection level** to “Bearer Token” with value `{{token}}`.

5. **Run** any request in the collection to verify functionality.

---

## 🔑 `.env.example`

Copy this file to `.env` and fill in your values:

```dotenv
APP_NAME=wallet-service
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fintech_app
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8000
```

---

## 📄 License

This project is licensed under the **MIT License**.
See [LICENSE](LICENSE) for details.

```
```
