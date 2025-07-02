# 🚀 Endpoint Auto-Save untuk n8n

## Endpoint Utama

### URL untuk Auto-Save

```
POST http://172.29.192.1:8000/api/questions/auto-save
POST http://172.29.192.1:8000/api/questions/n8n-save
```

## Konfigurasi n8n untuk Auto-Save

### HTTP Request Node Configuration

| Setting          | Value                                           |
| ---------------- | ----------------------------------------------- |
| **Method**       | `POST`                                          |
| **URL**          | `http://localhost:8000/api/questions/auto-save` |
| **Content-Type** | `application/json`                              |
| **Body Type**    | `JSON`                                          |

### JSON Body Format

Gunakan format JSON array yang sudah Anda berikan:

```json
[
  {
    "question": "Apakah aplikasi Android akan otomatis diberikan akses internet?",
    "options": {
      "A": "Ya, aplikasi Android akan diberikan akses internet otomatis.",
      "B": "Tidak, aplikasi Android harus menghubungi server agar dapat mengakses internet.",
      "C": "Ya, aplikasi Android akan mengakses internet secara otomatis tanpa diperlukan permission",
      "D": "Tidak, aplikasi Android terlindungi dengan firewall dan tidak bisa mengakses internet."
    },
    "answer": "B",
    "explanation": "Aplikasi Android tidak bisa mengakses internet secara otomatis, sehingga harus diberikan permission untuk dapat mengakses internet."
  },
  {
    "question": "Synchronous programming apakah blocking atau non-blocking?",
    "options": {
      "A": "Synchronous programming adalah programming yang blocking",
      "B": "Synchronous programming adalah programming yang non-blocking",
      "C": "Synchronous programming adalah programming yang dapat berjalan secara paralel",
      "D": "Synchronous programming adalah programming yang dapat membuat aplikasi berjalan cepat"
    },
    "answer": "A",
    "explanation": "Synchronous programming adalah programming yang harus menunggu sebelum melanjutkan, sehingga ini dikenal juga sebagai blocking programming."
  }
]
```

## Proses yang Terjadi

### 1. Auto-Save ke Database

- ✅ Semua soal akan **otomatis tersimpan** ke database
- ✅ Validasi otomatis untuk setiap soal
- ✅ Error handling untuk soal yang tidak valid

### 2. Setup Edit Form

- ✅ Soal pertama akan **otomatis ter-load** di form edit
- ✅ Semua soal tersimpan akan ditampilkan di sidebar
- ✅ User bisa langsung edit soal yang sudah tersimpan

### 3. Response Success

```json
{
  "success": true,
  "message": "16 soal berhasil disimpan otomatis dari n8n!",
  "data": [
    {
      "id": 1,
      "question": "...",
      "options": {...},
      "answer": "B",
      "explanation": "..."
    }
  ],
  "total_processed": 16,
  "total_saved": 16,
  "total_errors": 0,
  "form_url": "http://localhost:8000/questions/edit"
}
```

## Workflow Lengkap

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   n8n sends     │───▶│  Laravel API    │───▶│  Auto-Save to   │───▶│  Edit Form      │
│   JSON array    │    │  /auto-save     │    │  Database       │    │  with Data      │
│                 │    │                 │    │                 │    │                 │
│ 16 questions    │    │ Parse & Validate│    │ Save all 16     │    │ Load first Q    │
│ in JSON format  │    │ each question   │    │ questions       │    │ Show all in     │
│                 │    │                 │    │                 │    │ sidebar         │
└─────────────────┘    └─────────────────┘    └─────────────────┘    └─────────────────┘
```

## URL untuk Akses Edit Form

Setelah auto-save berhasil, buka:

```
http://localhost:8000/questions/edit
```

## Fitur Edit Form

### ✅ Form Otomatis Terisi

- Soal pertama dari data n8n akan otomatis ter-load di form
- Semua field sudah terisi dan siap untuk diedit

### ✅ Sidebar Soal Tersimpan

- Menampilkan semua soal yang baru disimpan
- Klik "Edit" untuk memuat soal ke form edit
- Menampilkan ID, preview pertanyaan, dan jawaban

### ✅ Update & Edit

- Edit soal langsung di form
- Update ke database dengan tombol "Update Soal"
- Validasi otomatis untuk semua field

### ✅ Navigasi Mudah

- Tombol "Tambah Baru" untuk membuat soal baru
- Tombol "Lihat Semua" untuk melihat semua soal
- Clear form untuk reset form

## Konfigurasi n8n Step-by-Step

### 1. Tambah HTTP Request Node

```
Node Type: HTTP Request
```

### 2. Set URL & Method

```
URL: http://localhost:8000/api/questions/auto-save
Method: POST
```

### 3. Set Headers

```
Content-Type: application/json
```

### 4. Set Body

```
Body Type: JSON
Body: [copy paste JSON array Anda]
```

### 5. Test Request

- Execute node di n8n
- Cek response sukses
- Buka http://localhost:8000/questions/edit
- Verify data tersimpan dan ter-load di form

## Endpoint Alternatif

Jika `/auto-save` tidak bekerja, coba:

| Endpoint                      | Purpose               | Method   |
| ----------------------------- | --------------------- | -------- |
| `/api/questions/n8n-save`     | Alternative auto-save | POST/ANY |
| `/api/questions/debug`        | Debug request         | POST/ANY |
| `/api/questions/simulate-n8n` | Test simulation       | GET/POST |

## Troubleshooting

### Jika Auto-Save Gagal

1. Cek endpoint debug: `/api/questions/debug`
2. Pastikan Content-Type: `application/json`
3. Pastikan JSON format valid
4. Cek logs: `storage/logs/laravel.log`

### Jika Edit Form Kosong

1. Refresh halaman `/questions/edit`
2. Cek apakah data tersimpan di database
3. Coba akses soal manual: `/questions/{id}/load`

Sekarang sistem akan:

1. ✅ **Auto-save** semua 16 soal ke database
2. ✅ **Auto-load** soal pertama di edit form
3. ✅ **Tampilkan semua** soal di sidebar untuk edit
4. ✅ **Allow editing** setiap soal yang tersimpan
