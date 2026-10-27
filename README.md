# chatbot-web

Website frontend untuk chatbot layanan mahasiswa berbasis **RAG**. Folder ini berisi halaman chat (PHP/HTML/CSS/JavaScript) yang dilayani oleh **Apache**, dan meneruskan pertanyaan ke backend Flask di folder `chatbot-rag`. Dibuat untuk praktikum mata kuliah Kecerdasan Buatan.

> Chatbot ini hanya demo pembelajaran dengan dokumen contoh, bukan layanan resmi kampus.

## Cara Kerja Singkat

```
Browser -> Apache (index.php + assets/app.js)
        -> chat.php (PHP + cURL)
        -> Flask (127.0.0.1:5000/api/chat)
        -> RAG + Gemini API
        -> JSON -> chat.php -> Browser
```

- `index.php` menampilkan antarmuka percakapan.
- `assets/app.js` mengirim pertanyaan lewat `fetch()` ke `chat.php` (bukan langsung ke Gemini atau Flask).
- `chat.php` bertindak sebagai perantara (proxy): meneruskan pertanyaan ke Flask lewat HTTP POST berformat JSON, lalu mengembalikan jawabannya. Dengan begitu tidak diperlukan konfigurasi CORS.
- **API key tidak ada di folder ini.** Key hanya berada di `.env` pada backend `chatbot-rag`.

## Struktur Folder

```
chatbot-web/
  hello.php        tes bahwa Apache dan PHP berjalan
  index.php        halaman chatbot (versi final)
  chat.php         penghubung PHP -> Flask API
  assets/
    style.css      tampilan
    app.js         logika percakapan di browser
```

## Kebutuhan

- Apache + PHP, misalnya **XAMPP** atau **Laragon** (MySQL tidak diperlukan)
- Ekstensi PHP **cURL** dan **mbstring** aktif
- Backend `chatbot-rag` sudah berjalan (lihat README di folder tersebut)

## Instalasi

1. Salin folder `chatbot-web` ke folder web server:
   - XAMPP: `C:\xampp\htdocs\chatbot-web`
   - Laragon: `C:\laragon\www\chatbot-web`
2. Nyalakan **Apache** (pastikan yang aktif adalah Apache, bukan Nginx, bila memakai Laragon).
3. Tes dulu dengan membuka `http://localhost/chatbot-web/hello.php`. Halaman "Hello World" harus tampil.

## Menjalankan

Dua layanan harus aktif bersamaan:

1. **Apache** menyala.
2. **Flask** berjalan di folder `chatbot-rag`:
   ```
   python -m flask --app api_app run --debug --host 127.0.0.1 --port 5000
   ```
3. Buka `http://localhost/chatbot-web/` dan ajukan pertanyaan, misalnya:
   - Berapa hari proses surat aktif kuliah?
   - Kapan laboratorium pemrograman buka?

Selalu buka lewat `http://localhost/...`, **jangan** klik dua kali file atau memakai `file://`.

Jika Apache memakai port selain 80 (misalnya 8080), gunakan `http://localhost:8080/chatbot-web/`.

## Pengujian yang Disarankan

| Skenario | Hasil yang diharapkan |
|---|---|
| Pertanyaan ada di dokumen | Jawaban dengan rujukan `[D...]` dan baris "Sumber chunk" |
| Pertanyaan tidak ada di dokumen | Informasi tidak ditemukan dalam dokumen |
| Flask dimatikan, Apache hidup | Pesan "Kesalahan: Flask tidak dapat dihubungi." |
| Apache dimatikan | Website tidak dapat dimuat (ERR_CONNECTION_REFUSED) |

Tab **Network** di browser (F12) harus menunjukkan permintaan menuju `chat.php`, bukan ke Gemini.

## Troubleshooting

| Gejala | Solusi |
|---|---|
| `localhost` tidak terbuka | Pastikan Apache berjalan, cek bentrok port 80 |
| Kode PHP tampil mentah | Buka lewat `http://localhost/...`, bukan `file://` |
| Halaman polos tanpa warna | Periksa `assets/style.css`, lalu hard refresh `Ctrl+F5` |
| Tombol tidak bereaksi | Periksa Console browser (F12) dan jalur `assets/app.js` |
| "Ekstensi PHP cURL belum aktif" | Aktifkan `extension=curl` di `php.ini` (Laragon: menu PHP > Extensions > curl), lalu restart Apache |
| `Call to undefined function mb_strlen` | Aktifkan ekstensi `mbstring`, atau ganti `mb_strlen` dengan `strlen` |
| "Flask tidak dapat dihubungi" (502) | Pastikan Flask berjalan di `127.0.0.1` port `5000` |

## Keamanan

- Jangan menaruh API key, file `.env`, atau kode backend Python di folder ini. Folder web bisa diakses lewat Apache.
- Jangan memasukkan password atau data pribadi pada kolom pertanyaan.
- Konfigurasi ini untuk **latihan lokal**, bukan sistem siap produksi.

## Catatan

Jawaban chatbot berasal dari model dan bisa keliru. Cocokkan dengan dokumen sumber di `chatbot-rag/knowledge/panduan_layanan.txt`.