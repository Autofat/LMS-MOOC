# Unified N8N Status Endpoint Documentation

## Overview

Single endpoint that handles all loading states for N8N processing: success, error, progress, and completion.

## Endpoint

**URL:** `/api/n8n/completion/{materialId?}`
**Methods:** `GET` (frontend polling) and `POST` (n8n updates)

## Frontend Usage (GET Requests)

The frontend polls this endpoint to check status:

```javascript
GET / api / n8n / completion / { materialId };
```

### Response Format:

```json
{
    "success": true,
    "status": "processing|progress|completed|error",
    "completed": true|false,
    "error": true|false,
    "progress": true|false,
    "data": {
        "status": "processing|progress|completed|error",
        "message": "Status message",
        "material_id": 123,
        "questions_count": 58,
        "updated_at": "2025-08-04T08:30:14.139942Z",
        "error_details": "Error details (only for error status)"
    }
}
```

### Status Types:

- **`processing`** - Initial state, n8n hasn't sent any updates yet
- **`progress`** - N8N sent a progress update
- **`completed`** - N8N finished successfully
- **`error`** - N8N encountered an error

## N8N Usage (POST Requests)

N8N sends status updates to this endpoint:

```javascript
POST /api/n8n/completion
Content-Type: application/json
```

### Request Format:

#### Success Completion:

```json
{
  "material_id": 123,
  "status": "completed",
  "message": "Flow has finished successfully!"
}
```

#### Error:

```json
{
  "material_id": 123,
  "status": "error",
  "message": "Failed to process PDF",
  "error_details": "PDF file is corrupted or unreadable"
}
```

#### Progress Update:

```json
{
  "material_id": 123,
  "status": "progress",
  "message": "Processing PDF content..."
}
```

## Frontend Behavior

### Success Flow:

1. User clicks "Generate Soal"
2. Frontend starts polling `/api/n8n/completion/{materialId}`
3. Initially returns `status: "processing"`
4. N8N posts completion: `status: "completed"`
5. Frontend detects `completed: true` and shows success
6. Loading stops, page reloads

### Error Flow:

1. User clicks "Generate Soal"
2. Frontend starts polling `/api/n8n/completion/{materialId}`
3. N8N posts error: `status: "error"`
4. Frontend detects `error: true` and shows error message
5. Loading stops with error notification

### Progress Flow:

1. User clicks "Generate Soal"
2. Frontend starts polling `/api/n8n/completion/{materialId}`
3. N8N posts progress: `status: "progress"`
4. Frontend detects `progress: true` and continues polling
5. Eventually N8N posts completion or error

## Configuration

### N8N Webhook URL:

```
http://192.168.13.193:8000/api/n8n/completion
```

### Frontend Polling:

- **Interval:** Every 3 seconds
- **Endpoint:** `GET /api/n8n/completion/{materialId}`
- **Timeout:** No timeout (polls until completion or error)

## Cache Management

- **Cache Key:** `n8n_completion_material_{materialId}`
- **Duration:** 10 minutes
- **Clear Cache:** `DELETE /api/questions/cache/{materialId}`

## Benefits

✅ **Single endpoint** for all loading states
✅ **Error handling** with detailed error messages
✅ **Progress updates** for long-running processes
✅ **Unified interface** for both frontend and N8N
✅ **Proper caching** with automatic cleanup
