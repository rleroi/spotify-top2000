<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Services\PlaylistService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class PlaylistController extends Controller
{
    public function dashboard(PlaylistService $playlistService): Response
    {
        $playlist = Auth::user()->playlists()->with('songs')->latest()->first();
        if (!$playlist) {
            $user = Auth::user();
            $playlistService->setUser($user);
            $playlistService->insertTopTracks();
            $playlist = Auth::user()->playlists()->with('songs')->latest()->first();
        }

        return Inertia::render('Dashboard', [
            'playlist' => $playlist,
        ]);
    }

    public function global(PlaylistService $playlistService): Response
    {
        $playlist = Playlist::query()->with('songs')->where('name', 'global')->latest()->first();

        if (!$playlist) {
            $playlistService->updateGlobalPlaylist();
            $playlist = Playlist::query()->with('songs')->where('name', 'global')->latest()->first();
        }

        return Inertia::render('Global', [
            'playlist' => $playlist,
        ]);
    }

    public function insertTopTracks(PlaylistService $playlistService): void
    {
        $user = Auth::user();
        $playlistService->setUser($user);
        $playlistService->insertTopTracks();
    }

    public function updateGlobalPlaylist(PlaylistService $playlistService): void
    {
        if (Auth::user()->spotify_id !== config('services.spotify.playlist_owner')) {
            abort(403);
        }

        $playlistService->updateGlobalPlaylist();
    }
}
