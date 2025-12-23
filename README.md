
# Mạng Xã Hội (Laravel + Supabase)

Dự án này là một ứng dụng mạng xã hội đơn giản được xây dựng bằng Laravel 12 kết hợp với Supabase đóng vai trò là Backend-as-a-Service

Nếu muốn trải nghiệm ứng dụng thì người dùng có thể vào website : **www.convey.ink** để trải nghiệm.

## 1. Yêu cầu hệ thống

Trước khi bắt đầu, hãy đảm bảo máy tính đã cài đặt:

- **PHP**: Phiên bản >= 8.2
- **Composer**: Trình quản lý thư viện cho PHP.
- **Node.js & NPM**: Để build giao diện (Vite).

## 2. Cài đặt dự án

Mở terminal (CMD/PowerShell/Terminal) tại thư mục dự án và chạy lần lượt các lệnh sau:

### Bước 1: Cài đặt các thư viện phụ thuộc
```bash
# Cài đặt thư viện PHP (Laravel framework, Guzzle, v.v.)
composer install

# Cài đặt thư viện Frontend
npm install
```

### Bước 2: Thiết lập file môi trường
Tạo Key mã hóa cho ứng dụng:
```bash
php artisan key:generate
```

## 3. Chạy dự án

Bạn cần mở **2 cửa sổ Terminal** để chạy song song:

**Terminal 1: Chạy Laravel Server**
```bash
php artisan serve
```

**Terminal 2: Chạy Vite (Build CSS/JS)**
```bash
npm run dev
```

Sau đó, truy cập vào đường dẫn hiển thị ở Terminal 1 (thường là `http://127.0.0.1:8000`) để trải nghiệm website.


