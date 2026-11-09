=== Biteship Shipping ===
Contributors: biteship
Tags: shipping, WooCommerce, biteship, e-commerce
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.6.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0
WC tested up to: 10.8
Requires Plugins: woocommerce

Plugin pengiriman WooCommerce dengan berbagai ekspedisi untuk pengiriman Reguler, Instan, dan Kargo.

== Description ==

Biteship Shipping adalah plugin ongkir WooCommerce yang membantu bisnis menampilkan tarif pengiriman secara otomatis di halaman checkout, menyediakan berbagai pilihan ekspedisi, dan memproses pengiriman dari toko online melalui Biteship.

Plugin ini cocok untuk pemilik toko WooCommerce yang ingin mengelola pengiriman dengan lebih praktis, memberikan transparansi ongkir kepada pelanggan, dan mengembangkan kanal penjualan sendiri di luar marketplace.

= Fitur Utama =

* **Cek ongkir otomatis di checkout** — Tampilkan tarif dan pilihan layanan pengiriman berdasarkan alamat asal, tujuan, berat, dan dimensi produk.
* **Dukungan multi-ekspedisi** — Sediakan pilihan pengiriman reguler, next day, same day, instan, hingga kargo sesuai paket dan cakupan layanan.
* **Pembuatan resi terintegrasi** — Proses pesanan WooCommerce menjadi pengiriman Biteship tanpa memasukkan ulang data pelanggan secara manual.
* **Pelacakan pengiriman** — Pantau status pengiriman berdasarkan pembaruan yang diterima dari ekspedisi.
* **Promo dan penyesuaian ongkir** — Atur subsidi, diskon, kenaikan, atau penurunan ongkos kirim sesuai strategi bisnis.
* **Integrasi tanpa coding** — Hubungkan akun Biteship dengan WooCommerce menggunakan API key melalui halaman pengaturan plugin.
* **Fitur pengiriman lanjutan** — Gunakan fitur seperti kurir toko sendiri, pengiriman instan, pilihan titik lokasi, dan halaman tracking custom sesuai paket dan konfigurasi akun.

= Ekspedisi yang Tersedia =

Biteship terhubung dengan berbagai ekspedisi Indonesia untuk layanan reguler, next day, same day, instan, dan kargo.

Pilihan ekspedisi dan jenis layanan dapat berbeda berdasarkan:

* paket berlangganan;
* lokasi penjemputan;
* alamat tujuan;
* berat dan dimensi kiriman;
* cakupan operasional ekspedisi;
* ketersediaan layanan pada akun Biteship.

Tarif dan pilihan layanan ditampilkan berdasarkan data yang tersedia saat pelanggan melakukan checkout.

Lihat daftar ekspedisi dan paket terbaru di: https://biteship.com/id/produk/plugin/woocommerce

= Persyaratan Penggunaan =

Sebelum menggunakan plugin, pastikan:

* WooCommerce telah terpasang dan aktif;
* WordPress dan PHP memenuhi versi minimum plugin;
* Anda memiliki akun serta paket WooCommerce Biteship yang aktif;
* API key Biteship telah tersedia;
* shipping zone Indonesia telah dikonfigurasi;
* alamat dan kode pos toko telah diisi;
* setiap produk memiliki informasi berat dan dimensi yang benar;
* tema atau checkout builder mendukung metode pengiriman WooCommerce.

Kinerja plugin dapat dipengaruhi oleh tema, custom code, checkout builder, plugin cache, plugin keamanan, atau plugin pihak ketiga lainnya.

== External Services ==

Plugin ini terhubung dengan sistem Biteship untuk menyediakan fungsi pengiriman pada toko WooCommerce.

**Biteship API**

* **Tujuan:** Menghitung ongkir, menampilkan layanan ekspedisi, memproses pengiriman, menghasilkan nomor resi, dan memperbarui status pengiriman.
* **Data yang dapat dikirim:** Alamat asal dan tujuan, nama dan nomor telepon penerima, informasi pesanan, berat, dimensi, nilai barang, serta detail lain yang dibutuhkan untuk memproses pengiriman.
* **Kapan data dikirim:** Saat tarif pengiriman dihitung, order pengiriman dibuat, resi diproses, atau status pengiriman diperbarui.
* **Ketentuan Penggunaan:** https://biteship.com/id/ketentuan-penggunaan
* **Kebijakan Privasi:** https://biteship.com/id/kebijakan-privasi

Dengan menggunakan plugin ini, pengguna memahami bahwa toko WooCommerce akan berkomunikasi dengan sistem Biteship dan mitra ekspedisi untuk menjalankan layanan pengiriman.

== Kebijakan Pembatalan dan Pengembalian Dana ==

Biaya paket berlangganan yang telah dibayarkan pada dasarnya tidak dapat dikembalikan apabila permintaan disebabkan oleh:

* perubahan pikiran setelah pembayaran;
* kesalahan memilih paket;
* fitur atau ekspedisi yang dibutuhkan tidak termasuk dalam paket;
* website tidak memenuhi persyaratan sistem;
* konfigurasi website tidak diselesaikan dengan benar;
* konflik dengan tema, custom code, atau plugin pihak ketiga;
* layanan ekspedisi tidak tersedia untuk lokasi tertentu;
* kendala yang berasal dari sistem atau kebijakan ekspedisi.

Pengguna bertanggung jawab memeriksa persyaratan sistem, kompatibilitas website, cakupan fitur, pilihan ekspedisi, dan kesesuaian paket sebelum melakukan pembayaran.

== Troubleshooting ==

Jika ongkir tidak muncul di checkout, pastikan:

* API key aktif dan telah dimasukkan dengan benar;
* shipping zone Indonesia telah dibuat;
* alamat asal dan kode pos telah diisi;
* alamat tujuan pelanggan lengkap;
* berat dan dimensi produk tidak kosong;
* ekspedisi telah diaktifkan;
* WooCommerce dan plugin Biteship menggunakan versi yang didukung;
* tidak terdapat konflik dengan plugin checkout atau shipping lainnya.

Apabila masalah masih terjadi, hubungi tim Biteship dengan menyertakan URL website, versi WordPress, WooCommerce, PHP, tema, daftar plugin terkait, dan screenshot kendala.

== Installation ==

1. Instal dan aktifkan plugin "Biteship Shipping".
2. Daftar atau masuk ke dashboard Biteship.
3. Aktifkan paket WooCommerce yang sesuai.
4. Salin API key dari menu integrasi Biteship.
5. Buka "WooCommerce > Settings > Integrations > Biteship".
6. Masukkan API key pada kolom yang tersedia.
7. Lengkapi alamat, nomor telepon, dan kode pos pengirim.
8. Tambahkan Biteship sebagai metode pengiriman pada shipping zone Indonesia.
9. Pilih ekspedisi dan layanan yang ingin ditampilkan.
10. Lakukan simulasi checkout sebelum menerima transaksi pelanggan.

Panduan aktivasi: [Cara Mudah Aktivasi Plugin Biteship](https://help.biteship.com/hc/id/articles/10968350540313-Cara-Mudah-Aktivasi-Plugin-Biteship-dalam-9-Langkah)

== Frequently Asked Questions ==

= Apakah plugin Biteship gratis? =

Plugin dapat diunduh dari WordPress Plugin Directory. Penggunaan layanan pengiriman WooCommerce membutuhkan paket berlangganan Biteship yang aktif.

= Apakah saya perlu memiliki kemampuan coding? =

Tidak. Konfigurasi dasar dapat dilakukan melalui dashboard Biteship dan pengaturan WooCommerce menggunakan API key.

= Mengapa ongkir tidak muncul di halaman checkout? =

Penyebab yang umum meliputi shipping zone yang belum tepat, kode pos tidak valid, berat atau dimensi produk belum diisi, API key tidak aktif, ekspedisi belum dipilih, atau konflik dengan tema dan plugin lain.

= Apakah semua ekspedisi tersedia di seluruh Indonesia? =

Tidak selalu. Ketersediaan ekspedisi bergantung pada paket, lokasi asal, tujuan, jenis layanan, dan cakupan operasional masing-masing ekspedisi.

= Apakah saya dapat memilih ekspedisi yang ditampilkan? =

Pengguna dapat memilih ekspedisi dan layanan yang ingin ditampilkan sesuai paket serta konfigurasi yang tersedia pada akun.

= Apakah plugin mendukung pengiriman instan? =

Ya, untuk paket, ekspedisi, dan area layanan yang mendukung pengiriman instan.

= Apakah saya dapat mencetak resi? =

Nomor resi dan label pengiriman dapat diproses setelah order pengiriman berhasil dibuat sesuai alur yang tersedia pada plugin dan dashboard Biteship.

= Apakah pembayaran paket dapat dikembalikan? =

Pembayaran pada dasarnya tidak dapat dikembalikan karena alasan perubahan pikiran, kesalahan memilih paket, atau kendala yang berasal dari konfigurasi dan kompatibilitas website pengguna.

== Tautan Dukungan ==

Video tutorial penggunaan:

* Daftar & setting plugin: [Tonton video tutorial](https://www.youtube.com/watch?v=RAyCXmcY6xo)
* Proses pesanan: [Tonton video tutorial](https://www.youtube.com/watch?v=La3ENs1mWPY)

Panduan penggunaan: [Help Center Biteship](https://help.biteship.com/hc/id)

Informasi paket: [Halaman produk WooCommerce Biteship](https://biteship.com/id/produk/plugin/woocommerce)

== Screenshots ==

1. Integration
2. General Settings
3. Shipping Zone
4. Ongkir di halaman checkout
5. Dashboard Biteship

== Changelog ==
= 1.6.0 =
* Biteship new subscription
= 1.5.1 =
* Sync plugin versioning to SVN
= 1.3.0 =
* Update shipment cancelation won't cancel woocommerce order
* Prevent double shipment creation
= 1.2.4 =
* Fix courier_not_found sync status
= 1.2.3 =
* Make support and tutorial links clickable in plugin readme
= 1.2.2 =
* Update minimum PHP requirement to 7.4
* Update tested compatibility for WooCommerce 10.8 and WordPress 6.8
= 1.2.1 =
* Fixing complete status on delivered checkbox
= 1.2.0 =
* Enable store coordinates for standard users
= 1.1.3 =
* Update deprecated woocommerce hooks
= 1.1.2 =
* Update COD value so it's includes shipping total
= 1.1.1 =
* Fixing mapbox not showing in some users (Premium)
= 1.1.0 =
* Add option to activate location dropdown in checkout page
= 1.0.10 =
* Update versioning
= 1.0.9 =
* Handle all item weight unit
= 1.0.8 =
* Add sku in item's information
= 1.0.7 =
* Add paxel as courier that needed coordinates
= 1.0.6 =
* Update metadata
= 1.0.5 =
* Adjust build system to allow automatic deployment
= 1.0.4 =
* Add option to automatically fill address using maps location (Premium)
* Increase request timeout upon calling Biteship API.
* Improve maps stability.
= 1.0.3 =
* Support product HTML description
= 1.0.2 =
* Eliminates warning and deprecation warning
= 1.0.1 =
* Add backward compatibility support
= 1.0.0 =
* Brand new Biteship Shipping implementation. No i18n support, yet.
