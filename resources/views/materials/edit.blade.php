<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Materi - {{ $material->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-6">
                    <a href="{{ route('materials.show', $material->id) }}"
                        class="text-blue-500 hover:text-blue-700 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Materi</h1>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current File Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">File Saat Ini</h3>
                        <div class="flex items-center space-x-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-800">{{ $material->file_name }}</p>
                                <p class="text-sm text-gray-600">{{ $material->file_size_human }}</p>
                            </div>
                            <a href="{{ route('materials.download', $material->id) }}"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Materi *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $material->title) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan judul materi...">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori (Opsional)
                        </label>
                        <select id="category" name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih kategori...</option>
                            <option value="Programming"
                                {{ old('category', $material->category) == 'Programming' ? 'selected' : '' }}>
                                Programming</option>
                            <option value="Database"
                                {{ old('category', $material->category) == 'Database' ? 'selected' : '' }}>Database
                            </option>
                            <option value="Network"
                                {{ old('category', $material->category) == 'Network' ? 'selected' : '' }}>Network
                            </option>
                            <option value="Web Development"
                                {{ old('category', $material->category) == 'Web Development' ? 'selected' : '' }}>Web
                                Development</option>
                            <option value="Mobile Development"
                                {{ old('category', $material->category) == 'Mobile Development' ? 'selected' : '' }}>
                                Mobile Development</option>
                            <option value="AI/ML"
                                {{ old('category', $material->category) == 'AI/ML' ? 'selected' : '' }}>AI/ML</option>
                            <option value="Security"
                                {{ old('category', $material->category) == 'Security' ? 'selected' : '' }}>Security
                            </option>
                            <option value="Other"
                                {{ old('category', $material->category) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan deskripsi materi...">{{ old('description', $material->description) }}</textarea>
                    </div>

                    <!-- Replace PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Ganti File PDF (Opsional)
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="pdf_file"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file PDF baru</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only"
                                            accept=".pdf" onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF hingga 10MB (kosongkan jika tidak ingin mengganti)
                                </p>
                                <p id="file-name" class="text-sm text-gray-700 font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between pt-6 border-t">
                        <a href="{{ route('materials.show', $material->id) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Update Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameElement.textContent = '→ ' + input.files[0].name;
            } else {
                fileNameElement.textContent = '';
            }
        }
    </script>
</body>

</html>
