<?php

namespace App\Services;

use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Handles media uploads and protected private storage.
 *
 * Files live in the private disk (storage/app/private) and are only exposed
 * through signed temporary URLs, never through direct public paths.
 */
class MediaService
{
    public function __construct(protected AuditService $audit)
    {
    }

    /**
     * Validates and stores a media file for a post.
     *
     * @return array{media_type: string, file_path: string, thumbnail_path: null|string, original_name: string, mime_type: string, size_bytes: int}
     */
    public function storeForPost(User $creator, UploadedFile $file, string $mediaType): array
    {
        $this->validateFile($file, $mediaType);

        $relativePath = $this->storePrivate($file, "posts/{$creator->id}");

        return [
            'media_type' => $mediaType,
            'file_path' => $relativePath,
            'thumbnail_path' => null,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ];
    }

    /**
     * Stores a user avatar or cover on the private disk.
     */
    public function storeProfileImage(UploadedFile $file, string $folder, string $type = 'image'): string
    {
        $this->validateFile($file, $type);

        return $this->storePrivate($file, $folder);
    }

    /**
     * Builds a short-lived signed URL for a private media file.
     */
    public function temporaryUrl(string $path, ?int $seconds = null): string
    {
        $seconds ??= config('fanora.media.sign_url_seconds', 3600);

        return Storage::disk('private')->temporaryUrl($path, now()->addSeconds($seconds));
    }

    public function exists(string $path): bool
    {
        return filled($path) && Storage::disk('private')->exists($path);
    }

    public function path(string $path): string
    {
        return Storage::disk('private')->path($path);
    }

    public function avatarUrl(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        return Storage::disk('private')->temporaryUrl($path, now()->addDay());
    }

    public function delete(string $path): void
    {
        if (filled($path)) {
            Storage::disk('private')->delete($path);
        }
    }

    public function deleteMedia(PostMedia $media): void
    {
        $this->delete($media->file_path);

        if ($media->thumbnail_path) {
            $this->delete($media->thumbnail_path);
        }
    }

    protected function storePrivate(UploadedFile $file, string $folder): string
    {
        // SECURITY: use guessExtension from mime, not client-provided extension, to prevent double-extension attacks
        $ext = $file->guessExtension() ?: strtolower($file->getClientOriginalExtension());
        // whitelist extensions
        $ext = in_array($ext, ['jpeg','jpg','png','webp','gif','mp4','webm','mov'], true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : 'bin';
        $name = Str::uuid()->toString().'.'.$ext;

        return $file->storeAs($folder, $name, 'private');
    }

    protected function validateFile(UploadedFile $file, string $mediaType): void
    {
        $config = config('fanora.media');

        if ($mediaType === 'video') {
            abort_if($file->getSize() > $config['video_max_bytes'], 422, 'Arquivo excede o tamanho máximo de vídeo.');
        } else {
            abort_if($file->getSize() > $config['image_max_bytes'], 422, 'Arquivo excede o tamanho máximo de imagem.');
        }

        abort_if($file->getSize() === 0, 422, 'Arquivo vazio não permitido.');

        // Validate both extension and mime; use client extension lowercased
        $allowed = $mediaType === 'video' ? $config['video_mimes'] : $config['image_mimes'];
        $ext = strtolower($file->getClientOriginalExtension());
        // normalize jpeg
        $extNorm = $ext === 'jpeg' ? 'jpg' : $ext;
        $allowedNorm = array_map(fn($e) => $e === 'jpeg' ? 'jpg' : $e, $allowed);
        abort_if(! in_array($extNorm, $allowedNorm, true), 422, 'Extensão de arquivo não permitida.');
        // Also validate guessed extension matches allowed
        $guessed = strtolower($file->guessExtension() ?? '');
        if ($guessed !== '') {
            $guessedNorm = $guessed === 'jpeg' ? 'jpg' : $guessed;
            abort_if(! in_array($guessedNorm, $allowedNorm, true), 422, 'Extensão inferida não permitida.');
        }

        abort_if(! in_array($file->getMimeType(), [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'video/mp4',
            'video/webm',
            'video/quicktime',
        ], true), 422, 'Tipo MIME não permitido.');
        // Block executable / php disguised files
        abort_if(str_contains($file->getClientOriginalName(), '.php'), 422, 'Nome de arquivo inválido.');
    }
}