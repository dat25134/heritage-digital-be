<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Heritage Digital BE (Laravel 10)

Backend Laravel 10 với JWT Auth, phân quyền Spatie, Media Library, hàng đợi Redis, và tài liệu API qua L5‑Swagger(UI) + Scribe.

### Yêu cầu hệ thống
- PHP >= 8.1, ext: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- Composer >= 2
- MySQL 8.x
- Node.js 18+ (nếu build assets)
- Redis 6+ (dùng cho queue)
- OpenSSL (tạo JWT secret)

### Cài đặt nhanh

```bash
# 1) Cài dependencies PHP
composer install

# 2) Tạo file môi trường & app key
cp .env.example .env  # nếu chưa có .env, tạo theo mẫu bên dưới
php artisan key:generate

# 3) Thiết lập JWT secret
php artisan jwt:secret

# 4) Cấu hình DB trong .env, sau đó migrate & seed dữ liệu
php artisan migrate --seed

# 5) Liên kết storage công khai
php artisan storage:link
```

### Chạy ứng dụng

```bash
php artisan serve
# Health check:
# GET http://127.0.0.1:8000/api/ping  -> { "message": "pong" }
```

### Hàng đợi (Queues)
- Dùng Redis cho queue.
- Worker phục vụ media queue:

```bash
php artisan queue:work redis --queue=media
```

### Xác thực & Phân quyền
- Xác thực: JWT guard `auth:api` (php-open-source-saver/jwt-auth)
  - Header: `Authorization: Bearer <JWT_TOKEN>`
- Phân quyền: spatie/laravel-permission (guard `api`)
  - Seeder khởi tạo sẵn các vai trò: `admin`, `editor`, `researcher`, `viewer`
  - Quyền bao phủ các nhóm: images, image-intros, topics, videos, posts, papers, backups

### Users mặc định (Seeder)
- admin@example.com / password
- editor@example.com / password
- researcher@example.com / password
- viewer@example.com / password

### API chính (rút gọn)
- Health: `GET /api/ping` → pong
- Auth (v1):
  - `POST /api/v1/auth/register`
  - `POST /api/v1/auth/login`
  - `GET /api/v1/auth/me` (auth:api)
  - `POST /api/v1/auth/refresh` (auth:api)
  - `POST /api/v1/auth/logout` (auth:api)
  - Google OAuth: `GET /api/v1/auth/google/redirect`, `GET /api/v1/auth/google/callback` (sẽ cấu hình sau)
- Admin (auth:api + role:admin):
  - Roles CRUD, gán permissions cho role
  - Permission listing
  - Lấy/gán roles & permissions cho user
- Topics (auth:api + permissions):
  - CRUD topics; quản lý media theo topic; sắp xếp media
- Generic entity media (auth:api + permissions):
  - Upload/list/delete media theo entity/id/collection

### Media & Filesystems
- Dùng disk `public` cho các file media công khai (yêu cầu `php artisan storage:link`)
- URL public: `${APP_URL}/storage/...`

### Tài liệu API
- L5‑Swagger (UI): mặc định phục vụ tại `GET /api/documentation`
  - Build thủ công khi cần:
    ```bash
    php artisan l5-swagger:generate
    ```
- Scribe (tạo OpenAPI + HTML docs): phục vụ tại
  - HTML docs: `GET /docs`
  - OpenAPI (json): `GET /docs.openapi`
  - Postman collection: `GET /docs.postman`
  - Generate thủ công:
    ```bash
    php artisan scribe:generate
    ```
- Gợi ý: dùng L5‑Swagger để duyệt UI nhanh; dùng Scribe để sinh OpenAPI/Postman cho client.

### Biến môi trường mẫu (.env)
Sao chép và chỉnh lại cho môi trường của bạn. Lưu ý: không commit secrets.

```dotenv
APP_NAME="Heritage Digital"
APP_ENV=local
APP_KEY=base64:CHAY_LENH_PHP_ARTISAN_KEY_GENERATE
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

# Database - MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=heritage_digital
DB_USERNAME=root
DB_PASSWORD=secret

# Queue - Redis
QUEUE_CONNECTION=redis

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Filesystem
FILESYSTEM_DISK=public

# JWT
JWT_SECRET=CHAY_LENH_php_artisan_jwt:secret_DE_TAO
JWT_TTL=60
JWT_REFRESH_TTL=20160

# Scribe (để Try It Out có token mẫu; KHÔNG thêm "Bearer ")
SCRIBE_AUTH_BEARER=

# L5 Swagger
L5_SWAGGER_USE_ABSOLUTE_PATH=true
L5_FORMAT_TO_USE_FOR_DOCS=yaml

# Google OAuth (thiết lập sau)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=${APP_URL}/api/v1/auth/google/callback
```

### Lệnh Artisan hữu ích

```bash
# Migrations/Seeds
php artisan migrate
php artisan migrate:fresh --seed

# Cache/Config
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# JWT
php artisan jwt:secret
php artisan jwt:refresh

# Queues
php artisan queue:work redis --queue=media
php artisan queue:restart

# Docs
php artisan l5-swagger:generate
php artisan scribe:generate
```

### Khắc phục sự cố
- 401 Unauthorized: kiểm tra header `Authorization: Bearer <token>` và `JWT_SECRET`.
- 404 tài liệu Swagger: chạy `php artisan l5-swagger:generate`.
- 404 `/docs`: đảm bảo `config/scribe.php` `laravel.add_routes=true`, rồi `php artisan scribe:generate`.
- Media 404: chạy `php artisan storage:link` và kiểm tra `APP_URL`.
- Queue không xử lý media: kiểm tra Redis, `QUEUE_CONNECTION=redis`, và worker `queue:work redis --queue=media`.
