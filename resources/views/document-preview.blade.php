@php
    $record = $getRecord();
    $path = $record->file_path;
    $url = Storage::url($path);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
@endphp

<div class="w-full rounded-xl border bg-white shadow-sm overflow-hidden">

    @if ($ext === 'pdf')

        <div class="w-full h-[85vh]">
            <iframe
                src="{{ $url }}#toolbar=1&navpanes=0&scrollbar=1"
                class="w-full h-full border-0"
            ></iframe>
        </div>

    @elseif (in_array($ext, ['jpg','jpeg','png','webp']))

        <div class="p-4 flex justify-center bg-gray-50">
            <img
                src="{{ $url }}"
                class="max-h-[80vh] object-contain rounded-lg shadow"
            >
        </div>

    @else

        <div class="p-10 text-center space-y-4">
            <p class="text-gray-600 text-lg">
                Preview not supported
            </p>

            <a href="{{ $url }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-xl shadow hover:bg-primary-700 transition">
                ⬇ Download File
            </a>
        </div>

    @endif
</div>
