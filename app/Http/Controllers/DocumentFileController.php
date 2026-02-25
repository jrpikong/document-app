<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentFileController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function show(Document $document): StreamedResponse|Response
    {
        $this->authorize('download', $document);

        $path = (string) $document->file_path;
        $disk = Storage::disk('public');

        abort_unless($path !== '' && $disk->exists($path), 404, 'File not found.');

        $filename = $document->original_name ?: basename($path);

        return $disk->response($path, $filename, [
            'Content-Disposition' => 'inline; filename="' . addslashes($filename) . '"',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function download(Document $document): StreamedResponse|Response
    {
        $this->authorize('download', $document);

        $path = (string) $document->file_path;
        $disk = Storage::disk('public');

        abort_unless($path !== '' && $disk->exists($path), 404, 'File not found.');

        $filename = $document->original_name ?: basename($path);

        return $disk->download($path, $filename);
    }
}
