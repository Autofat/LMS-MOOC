# Troubleshooting N8N JSON Error

## Error: "JSON parameter needs to be valid JSON"

Error ini terjadi di n8n ketika mencoba mengirim data ke endpoint Laravel. Berikut adalah solusi langkah demi langkah:

## Solusi 1: Menggunakan Endpoint Baru yang Robust

Gunakan endpoint baru: `POST /api/questions/n8n`

Endpoint ini dapat menangani berbagai format data dan memberikan error message yang lebih jelas.

### N8N HTTP Node Configuration:

```
URL: http://YOUR_DOMAIN/api/questions/n8n
Method: POST
Body: JSON
```

## Solusi 2: Konfigurasi N8N HTTP Node

### Option A: Send as JSON Body

1. Di HTTP Request Node, pilih "Body" tab
2. Set "Body Content Type" ke "JSON"
3. Di "Body" field, masukkan data JSON:

```json
[
  {
    "question": "Apa ibu kota Indonesia?",
    "options": {
      "A": "Jakarta",
      "B": "Bandung",
      "C": "Surabaya",
      "D": "Medan"
    },
    "answer": "A",
    "explanation": "Jakarta adalah ibu kota Indonesia"
  }
]
```

### Option B: Send as Form Data

1. Di HTTP Request Node, pilih "Body" tab
2. Set "Body Content Type" ke "Form-Data"
3. Tambahkan field `json` dengan value JSON string:

```
Field Name: json
Field Value: [{"question":"Test","options":{"A":"A","B":"B","C":"C","D":"D"},"answer":"A"}]
```

### Option C: Send via Query Parameters

1. Set method ke GET atau POST
2. Di "Parameters" tab, tambahkan:

```
Name: json
Value: [{"question":"Test","options":{"A":"A","B":"B","C":"C","D":"D"},"answer":"A"}]
```

## Format Data yang Didukung

### Format 1: Array Langsung (Recommended)

```json
[
  {
    "question": "Pertanyaan 1?",
    "options": {
      "A": "Pilihan A",
      "B": "Pilihan B",
      "C": "Pilihan C",
      "D": "Pilihan D"
    },
    "answer": "A",
    "explanation": "Penjelasan jawaban"
  }
]
```

### Format 2: Object dengan Key 'soal'

```json
{
  "soal": [
    {
      "question": "Pertanyaan 1?",
      "options": {
        "A": "Pilihan A",
        "B": "Pilihan B",
        "C": "Pilihan C",
        "D": "Pilihan D"
      },
      "answer": "A",
      "explanation": "Penjelasan jawaban"
    }
  ]
}
```

## Debugging di N8N

### 1. Cek Output dari Node Sebelumnya

Pastikan node sebelumnya menghasilkan data dalam format yang benar.

### 2. Gunakan Code Node untuk Format Data

Jika data dari node sebelumnya tidak sesuai format, gunakan Code Node:

```javascript
// Contoh mengubah format data
const questions = items[0].json.data; // Sesuaikan dengan struktur data Anda

const formattedQuestions = questions.map((q) => ({
  question: q.pertanyaan || q.question,
  options: {
    A: q.pilihan_a || q.options?.A,
    B: q.pilihan_b || q.options?.B,
    C: q.pilihan_c || q.options?.C,
    D: q.pilihan_d || q.options?.D,
  },
  answer: q.jawaban || q.answer,
  explanation: q.penjelasan || q.explanation,
}));

return [{ json: formattedQuestions }];
```

### 3. Testing dengan Expression Editor

Di HTTP Request Node, gunakan expression untuk debug:

```javascript
{
  {
    JSON.stringify($json);
  }
}
```

## Response dari Endpoint

### Success Response:

```json
{
  "success": true,
  "message": "Successfully processed 5 questions",
  "data": {
    "total_questions": 5,
    "saved_count": 5,
    "parse_method": "raw_json",
    "errors": []
  }
}
```

### Error Response:

```json
{
  "success": false,
  "message": "No valid data received",
  "error_code": "NO_DATA",
  "debug": {
    "raw_content_length": 0,
    "content_type": "application/json",
    "suggestions": [
      "Make sure Content-Type is application/json",
      "Check that JSON data is properly formatted"
    ]
  }
}
```

## Checklist Troubleshooting

- [ ] URL endpoint benar: `/api/questions/n8n`
- [ ] Method: POST
- [ ] Content-Type: application/json
- [ ] Body berisi data JSON yang valid
- [ ] Struktur data sesuai format yang didukung
- [ ] Field required tersedia: question, options (A,B,C,D), answer
- [ ] Answer value adalah A, B, C, atau D
- [ ] Tidak ada karakter escape yang berlebihan

## Contoh Testing dengan Curl

```bash
# Test dengan format array langsung
curl -X POST http://your-domain/api/questions/n8n \
  -H "Content-Type: application/json" \
  -d '[{"question":"Test Question","options":{"A":"Option A","B":"Option B","C":"Option C","D":"Option D"},"answer":"A","explanation":"Test explanation"}]'

# Test dengan format object
curl -X POST http://your-domain/api/questions/n8n \
  -H "Content-Type: application/json" \
  -d '{"soal":[{"question":"Test Question","options":{"A":"Option A","B":"Option B","C":"Option C","D":"Option D"},"answer":"A"}]}'
```

## Alternatif Jika Masih Error

Jika masih mengalami error, coba endpoint lain:

1. `/api/questions/auto-save` - Endpoint lama yang sudah teruji
2. `/api/questions/debug` - Untuk melihat data mentah yang diterima server
3. `/api/questions/test` - Untuk testing sederhana

Atau gunakan method GET dengan query parameter:

```
GET /api/questions/n8n?json=[{"question":"Test","options":{"A":"A","B":"B","C":"C","D":"D"},"answer":"A"}]
```
