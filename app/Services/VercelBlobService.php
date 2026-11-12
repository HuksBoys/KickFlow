<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class VercelBlobService
{
    public function store(UploadedFile $file): string
    {
        $token = config('services.vercel_blob.token');

        if (! $token) {
            return $file->store('fields', 'public');
        }

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Api-Version' => '13',
                'X-Vercel-Blob-Store-Id' => $this->storeId($token),
                'X-Vercel-Blob-Access' => 'public',
                'X-Add-Random-Suffix' => '1',
                'X-Content-Type' => $file->getMimeType(),
            ])
            ->connectTimeout(3)
            ->timeout(15)
            ->withBody($file->get(), $file->getMimeType())
            ->put('https://vercel.com/api/blob/?pathname='.rawurlencode('fields/'.$file->getClientOriginalName()));

        try {
            $url = $response->throw()->json('url');
        } catch (ConnectionException|RequestException $exception) {
            throw new RuntimeException('Gagal mengunggah foto lapangan.', previous: $exception);
        }

        if (! is_string($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Vercel Blob tidak mengembalikan URL foto yang valid.');
        }

        return $url;
    }

    public function delete(string $url): void
    {
        $token = config('services.vercel_blob.token');

        if (! $token || ! str_contains($url, '.blob.vercel-storage.com/')) {
            Storage::disk('public')->delete($url);

            return;
        }

        try {
            Http::withToken($token)
                ->connectTimeout(3)
                ->timeout(15)
                ->post('https://vercel.com/api/blob/delete', ['urls' => [$url]])
                ->throw();
        } catch (ConnectionException|RequestException $exception) {
            report($exception);
        }
    }

    private function storeId(string $token): string
    {
        return explode('_', $token)[3] ?? '';
    }
}
