# Panduan Integrasi n8n dengan Laravel Question System

## Masalah yang Sering Terjadi

Berdasarkan log, masalah utama adalah n8n mengirim request dengan:

- `Content-Length: 0` (tidak ada konten)
- Tidak ada `Content-Type` header
- Body request kosong

## Solusi: Endpoint Khusus untuk n8n

Gunakan endpoint baru yang lebih fleksibel:

### URL Endpoint

```
POST/GET http://localhost:8000/api/questions/n8n-fill
POST/GET http://localhost:8000/api/questions/fill-n8n
```

## Konfigurasi n8n

### 1. HTTP Request Node Configuration

#### Method 1: POST dengan JSON Body (Recommended)

```
- Method: POST
- URL: http://localhost:8000/api/questions/n8n-fill
- Headers:
  * Content-Type: application/json
- Body:
  * Type: JSON
  * Data:
  {
    "questions": [
      {
        "question": "Apa ibukota Indonesia?",
        "options": {
          "A": "Jakarta",
          "B": "Bandung",
          "C": "Surabaya",
          "D": "Medan"
        },
        "answer": "A",
        "explanation": "Jakarta adalah ibukota Indonesia"
      }
    ]
  }
```

#### Method 2: GET dengan Query Parameters

```
- Method: GET
- URL: http://localhost:8000/api/questions/n8n-fill?data={"question":"Test","options":{"A":"1","B":"2","C":"3","D":"4"},"answer":"A"}
```

#### Method 3: POST dengan Form Data

```
- Method: POST
- URL: http://localhost:8000/api/questions/n8n-fill
- Headers:
  * Content-Type: application/x-www-form-urlencoded
- Body:
  * Type: Form
  * Fields:
    - data: {"question":"Test","options":{"A":"1","B":"2","C":"3","D":"4"},"answer":"A"}
```

#### Method 4: POST dengan Custom Header

```
- Method: POST
- URL: http://localhost:8000/api/questions/n8n-fill
- Headers:
  * X-Data: {"question":"Test","options":{"A":"1","B":"2","C":"3","D":"4"},"answer":"A"}
```

### 2. Format Data yang Didukung

#### Format 1: Array of Questions

```json
[
  {
    "question": "Siapa presiden pertama Indonesia?",
    "options": {
      "A": "Soekarno",
      "B": "Soeharto",
      "C": "Habibie",
      "D": "Gus Dur"
    },
    "answer": "A",
    "explanation": "Soekarno adalah presiden pertama Indonesia"
  }
]
```

#### Format 2: Object dengan key 'questions'

```json
{
  "questions": [
    {
      "question": "Berapa hasil 2+2?",
      "options": {
        "A": "3",
        "B": "4",
        "C": "5",
        "D": "6"
      },
      "answer": "B",
      "explanation": "2 + 2 = 4"
    }
  ]
}
```

#### Format 3: Object dengan key 'data'

```json
{
  "data": {
    "question": "Apa warna bendera Indonesia?",
    "options": {
      "A": "Merah Putih",
      "B": "Merah Hijau",
      "C": "Biru Putih",
      "D": "Kuning Hijau"
    },
    "answer": "A",
    "explanation": "Bendera Indonesia berwarna merah putih"
  }
}
```

#### Format 4: Single Question Object

```json
{
  "question": "Kapan Indonesia merdeka?",
  "options": {
    "A": "17 Agustus 1945",
    "B": "17 Agustus 1946",
    "C": "1 Juni 1945",
    "D": "20 Mei 1945"
  },
  "answer": "A",
  "explanation": "Indonesia merdeka pada 17 Agustus 1945"
}
```

## Troubleshooting

### Jika Masih Error "No data received"

1. **Cek HTTP Request Node di n8n:**

   - Pastikan URL benar
   - Pastikan Method dipilih (POST/GET)
   - Pastikan Body Type sesuai (JSON/Form/Raw)

2. **Cek Headers:**

   - Tambahkan `Content-Type: application/json` untuk JSON
   - Atau `Content-Type: application/x-www-form-urlencoded` untuk form data

3. **Coba Method Alternatif:**

   - Jika POST tidak bekerja, coba GET dengan query parameters
   - Jika JSON tidak bekerja, coba form data

4. **Test dengan curl dulu:**
   ```bash
   curl -X POST http://localhost:8000/api/questions/debug \
     -H "Content-Type: application/json" \
     -d '{"test": "from curl"}'
   ```

### Debug Information

Endpoint akan memberikan informasi debug lengkap jika ada masalah:

- Source method yang berhasil
- Headers yang diterima
- Content yang diterima
- Saran perbaikan

## Response Success

```json
{
  "success": true,
  "message": "Data berhasil disiapkan untuk mengisi form dari n8n",
  "data": {
    "question": "...",
    "option_a": "...",
    "option_b": "...",
    "option_c": "...",
    "option_d": "...",
    "answer": "A",
    "explanation": "..."
  },
  "total_questions": 1,
  "form_url": "http://localhost:8000/questions/create"
}
```

Setelah response success, user bisa membuka `form_url` dan form akan ter-isi otomatis dengan data dari n8n.
