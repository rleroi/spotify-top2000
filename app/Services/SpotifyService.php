<?php

declare(strict_types=1);

namespace App\Services;

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class SpotifyService
{
    private User $user;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Make an API request with token handling.
     */
    public function request(string $method, string $endpoint, array $options = []): Response
    {
        try {
            $response = Http::withToken($this->user->spotify_token)
                ->throw()
                ->$method(
                    'https://api.spotify.com/v1/' . $endpoint,
                    $options
                );
        } catch (RequestException $e) {
            if ($e->response->status() !== 401) {
                throw $e;
            }

            // Handle expired token
            $this->refreshToken();
            $options['headers']['Authorization'] = 'Bearer ' . $this->user->spotify_token;

            return Http::withToken($this->user->spotify_token)
                ->throw()
                ->$method(
                    'https://api.spotify.com/v1/' . $endpoint,
                    $options
                );
        }


        return $response;
    }

    /**
     * Refresh the Spotify token.
     */
    public function refreshToken(): void
    {
        $response = Http::asForm()->throw()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->user->spotify_refresh_token,
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->user->update([
                'spotify_token' => $data['access_token'],
                'spotify_refresh_token' => $data['refresh_token'] ?? $this->user->spotify_refresh_token,
            ]);
        } else {
            throw new Exception('Failed to refresh Spotify token');
        }
    }
}

