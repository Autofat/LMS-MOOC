<!DOCTYPE html>
<html>
<head>
    <title>Test PDF Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">Test PDF Upload</h1>
        
        <form action="{{ route('test.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2" style="color: rgba(28,88,113,0.9);">Select PDF File:</label>
                <input type="file" name="pdf_file" accept=".pdf" class="w-full p-2 rounded border-2" style="border-color: rgba(28,88,113,0.2); background: linear-gradient(135deg, rgba(245,158,11,0.05) 0%, rgba(251,191,36,0.05) 100%);">
            </div>
            
            <button type="submit" class="px-4 py-2 rounded transition-all font-semibold text-white" style="background: linear-gradient(135deg, rgba(28,88,113,0.8) 0%, rgba(34,108,137,0.9) 100%);">
                Test Upload
            </button>
        </form>
        
        <div class="mt-4 text-sm text-gray-600">
            <p>This form will test:</p>
            <ul class="list-disc list-inside">
                <li>PHP upload limits</li>
                <li>Storage permissions</li>
                <li>File validation</li>
                <li>Directory structure</li>
            </ul>
        </div>
    </div>
</body>
</html>
