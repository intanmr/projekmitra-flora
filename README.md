# projekmitra-flora
Mitra Flora adalah website jual beli tanaman hias berbasis laravel untuk dapat membantu proses penjualan tanaman hias secara online. Sistem ini terdapat 2 jenis pengguna yaitu admin dan customer. Admin dapat mengelola data produk tanaman dan data pesanan customer. Customer dapat melihat katalog tanaman, melakukan pemesanan, mengunggah bukti pembayaran, dan melihat riwayat pesanan. 

Sebelum menjalankan websitenya, pastikan jika perangkat sudah mempunyai PHP, Composer, Node.js dan NPM, MySQL, Laragon, Git, dan Browser. Selain itu nyalakan juga web server lokal. Jika menggunakan laragon, buka aplikasi laragon dan klik tombol Start All. Setelah itu, buatlah folder projek untuk menyimpan projek tersebut. Jika menggunakan laragon, buka Command Prompt lalu jalankan :

cd C:\laragon\www

mkdir tugas-pweb

cd tugas-pweb

Nanti folder ini akan digunakan untuk tempat menyimpan project Mitra Flora ini.

Dibawah ini adalah panduan instalasi dan cara untuk menjalankan website MitraFlora :

1. Clone Repository dari Github
   
   Setelah berada dalam foler yang telah dibuat tadi, Clone terlebih dahulu dengan menjalankan
   perintah :
   
   git clone https://github.com/intanmr/projekmitra-flora.git
   
   Setelah clone selesai, masuk ke folder projek yang telah dibuat yaitu dengan cd projekmitra
   flora. Pastikan jika sudah berisi file seperti artisan, composer.json, app, routes, dll. Jika
   sudah terdapat file tersebut maka folder projek sudah benar

   
3. Install Dependency Laravel
   
   Untuk menginstall dependency laravelnya jalankan perintah :
   
   composer install
   
   Apabila berhasil nanti akan muncul folder baru yaitu vendor.


5. Install Dependency Frontend
   
   Untuk menginstall dependency frontend seperti Vite, CSS, dan JavaScript jalankan perintah :
   
   npm install
   
   Apabila sudah berhasil akan muncul folder baru bernama node_modules

   
7. Membuat file .env
   
   Untuk membuat file .env dibuat dari file .env.example. Untuk membuatnya jalankan perintah :

   copy .env.example .env

   Jika berhasil, pada folder project tersebut akan muncul file .env

   
9. Melakukan konfigurasi pada file .env
    
   Atur pada .env seperti ini :
   
   APP_NAME="Mitra Flora"
   
   APP_ENV=local
   
   APP_DEBUG=true
   
   APP_URL=http://127.0.0.1:8000


   DB_CONNECTION=mysql
   
   DB_HOST=127.0.0.1
   
   DB_PORT=3306
   
   DB_DATABASE=siak_db
   
   DB_USERNAME=root

   DB_PASSWORD=


   SESSION_DRIVER=database
   
   SESSION_LIFETIME=120

   
   FILESYSTEM_DISK=public

    
11. Generate Application Key
    
    Selanjutnya jalankan perintah :

    php artisan key:generate

    Ini digunakan untuk membuat APP_KEY pada file .env, jikan berhasil akan muncul pesan
    Application key set successfully

    
13. Membuat Database di phpMyAdmin
    
    buka browser untuk masuk ke phpMyAdmin menggunakan : http://localhost/phpmyadmin

    Setelah phpMyAdmin terbuka buat database barunya dengan klik menu Database, lalu pada kolom
    nama database isi siak_db. Setelah itu klik tombol Create. Pastikan jika database siak_db
    sudah berhasil muncul di daftar databasenya

    
16. Migrasi Database dan Seeder
    
    Selanjutnya melakukan migration Laravel dengan menjalankan perintah :

    php artisan migrate --seed


18. Membuat Storage Link
    
    Agar gambar dapat muncul di browser jalankan perintah :

    php artisan storage:link


20. Membersihkan Cache Laravel
    
    Apabila sudah mengatur .env dan database selanjutntya jalankan perintah :

    php artisan optimize:clear

    Hal ini digunakan agar dapat membersihkan cache dan perubahan .env bisa terbaca dengan benar


22. Menjalankan Vite untuk CSS dan JS
    
    Selanjutnya jalankan perintah :

    npm run dev

    Hal ini digunakan untuk menjalankan perintah frontendnya

    
24. Menjalankan Server Laravel
    
    Selanjutnya buka Command Promt baru dengan perintah :

    cd C:\laragon\www\tugas-pweb\projekmitra-flora
    
    dan lanjutkan dengan perintah :

    php artisan serve

    lalu buka alamat tersebut pada browser


26. Membuka Website di Browser
    
    Akses http://127.0.0.1:8000 di browser, apabila semua langkah sudah benar maka projek mitra
    flora akan tampil

A. Cara untuk Login Admin 

Gunakan akun admin sebagai berikut : 

Email : admin.mitraflora@gmail.com 

Password : password123

Setelah login sebagai admin, pengguna dapat mengakses fitur : 

- Dashboard admin
- Kelola produk
- Tambah produk
- Edit produk
- Hapus produk
- Kelola pesanan
- Ubah status pesanan
- Hapus pesanan
- Live search produk
- Live search pesanan

B. Cara untuk Login Customer

- Buka halaman register.
- Isi nama lengkap.
- Isi email.
- Isi password.
- Isi konfirmasi password.
- Klik register.
- Login menggunakan akun customer yang sudah dibuat.

Setelah login sebagai customer, pengguna dapat mengakses fitur:

- Dashboard customer
- Katalog produk
- Detail produk
- Checkout atau pemesanan produk
- Upload bukti pembayaran
- Riwayat pesanan
- Live search katalog produk
- Live search riwayat pesanan
- Mode terang dan mode gelap

