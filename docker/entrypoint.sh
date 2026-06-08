#!/bin/sh

# Jalankan migrasi database Laravel secara otomatis saat container menyala
php artisan migrate --force

# Jalankan supervisor untuk menghidupkan Nginx & PHP-FPM
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
