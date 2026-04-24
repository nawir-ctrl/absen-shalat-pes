========================================================
UPDATE GUIDE - LARAVEL VPS (aaPanel)
Project: Sistem Absensi Pesantren
Server: VPS + aaPanel
=====================

## PENTING

Selalu lakukan backup sebelum update aplikasi.
JANGAN pernah menjalankan:

```
php artisan migrate:fresh
php artisan migrate:refresh
php artisan db:wipe
```

Perintah tersebut akan menghapus database.

========================================================

1. BACKUP PROJECT DAN DATABASE
   ========================================================

Backup folder project:

```
cp -r /www/wwwroot/absen.peskilkendari.com \
/www/wwwroot/absen.peskilkendari.com-backup
```

Backup database:

```
mysqldump -u DB_USER -p DB_NAME \
> /www/wwwroot/db-backup.sql
```

========================================================
2. UPLOAD SOURCE CODE VERSI TERBARU
===================================

Upload file project terbaru ke folder:

```
/www/wwwroot/absen.peskilkendari.com
```

JANGAN menimpa file berikut dari server:

```
.env
storage/app/public   (jika ada file upload)
database lama
```

========================================================
3. MASUK KE FOLDER PROJECT
==========================

```
cd /www/wwwroot/absen.peskilkendari.com
```

========================================================
4. PASTIKAN VERSI PHP SESUAI
============================

Website menggunakan PHP 8.2.

Gunakan binary PHP yang sama saat menjalankan
composer dan artisan.

Contoh:

```
/www/server/php/82/bin/php -v
```

========================================================
5. INSTALL DEPENDENCY (COMPOSER)
================================

Cek lokasi composer:

```
which composer
```

Install dependency:

```
/www/server/php/82/bin/php /usr/bin/composer install \
--no-dev --optimize-autoloader
```

Jika perlu update dependency:

```
/www/server/php/82/bin/php /usr/bin/composer update
```

========================================================
6. JALANKAN MIGRATION DATABASE
==============================

Migration hanya menambahkan perubahan database
tanpa menghapus data lama.

```
/www/server/php/82/bin/php artisan migrate
```

========================================================
7. BUILD FRONTEND (VITE)
========================

Jika server memiliki Node.js:

```
npm install
npm run build
```

Jika build dilakukan di lokal,
upload folder:

```
public/build
```

========================================================
8. CLEAR DAN CACHE LARAVEL
==========================

```
/www/server/php/82/bin/php artisan optimize:clear
/www/server/php/82/bin/php artisan config:cache
/www/server/php/82/bin/php artisan route:cache
/www/server/php/82/bin/php artisan view:cache
```

========================================================
9. PERMISSION FOLDER
====================

Pastikan folder berikut writable:

```
storage
bootstrap/cache
```

Perintah:

```
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

Jika perlu:

```
chown -R www:www /www/wwwroot/absen.peskilkendari.com
```

========================================================
10. TEST FITUR SETELAH UPDATE
=============================

Periksa fitur utama:

```
Login sistem
Dashboard
Data Santri
Tambah/Edit/Hapus Santri
Bulk Delete Santri
Absensi Shalat
Status Masbuk
Rekap Per Santri
Bulk Delete Rekap
Export PDF
```

========================================================
CHECKLIST UPDATE CEPAT
======================

```
cd /www/wwwroot/absen.peskilkendari.com

/www/server/php/82/bin/php /usr/bin/composer install \
--no-dev --optimize-autoloader

/www/server/php/82/bin/php artisan migrate

npm run build

/www/server/php/82/bin/php artisan optimize:clear
/www/server/php/82/bin/php artisan config:cache
/www/server/php/82/bin/php artisan route:cache
/www/server/php/82/bin/php artisan view:cache
```

========================================================
CATATAN
=======

Database lama tetap aman selama hanya menjalankan:

```
php artisan migrate
```

Jangan mengedit file:

```
vendor/composer/platform_check.php
```

Jika terjadi error PHP version mismatch,
pastikan composer dijalankan dengan
binary PHP yang sama dengan website.

========================================================
END OF FILE
===========
