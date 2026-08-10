FROM php:8.2-cli

# ติดตั้งแพ็กเกจและส่วนเสริมที่จำเป็นสำหรับ Laravel และ SQLite
RUN apt-get update -y && apt-get install -y \
    openssl \
    unzip \
    git \
    libsqlite3-dev \
    libonig-dev \
    libxml2-dev

# ติดตั้ง Composer (ตัวจัดการแพ็กเกจ PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# กำหนดโฟลเดอร์ทำงานหลักใน Docker
WORKDIR /app
COPY . /app

# ติดตั้งไลบรารีผ่าน Composer
RUN composer install --no-dev --optimize-autoloader

# ให้สิทธิ์เขียนไฟล์ในโฟลเดอร์ storage และ bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# คำสั่งเปิดใช้งานเว็บแอปพลิเคชัน
CMD php artisan config:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}