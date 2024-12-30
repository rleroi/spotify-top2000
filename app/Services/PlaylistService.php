<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class PlaylistService
{
    private const int INSERT_MAX = 100;
    private const int PER_PAGE = 20; // make sure this fits in MAX to not overflow the total numbers added
    private const int GLOBAL_MAX = 2000;
    private const int GLOBAL_PER_PAGE = 100;

    private User $user;

    public function __construct(private SpotifyService $spotifyService)
    {
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->spotifyService->setUser($user);
    }

    public function insertTopTracks(): void
    {
        DB::beginTransaction();
        $user = $this->user;
        $user->playlists()->delete();
        $playlist = $user->playlists()->create([
            'name' => $user->name . '\'s Top ' . self::INSERT_MAX . ' (last 6 months)',
        ]);

        $loopIndex = 0;
        $totalSaved = 0;
        do {
            $response = $this->spotifyService->request(
                'get',
                'me/top/tracks',
                [
                    //'time_range' => 'long_term',
                    'time_range' => 'medium_term',
                    'limit' => self::PER_PAGE,
                    'offset' => $loopIndex * self::PER_PAGE,
                ]
            );

            $songs = [];
            foreach ($response->json('items') as $track) {
                $songs[] = new Song([
                    'spotify_id' => Arr::get($track, 'id'),
                    'playlist_position' => self::INSERT_MAX - $totalSaved,
                    'playlist_id' => $playlist->id,
                    'spotify_artist_id' => Arr::get($track, 'artists.0.id', ''),
                    'artist' => Arr::get($track, 'artists.0.name', ''),
                    'name' => Arr::get($track, 'name', ''),
                    'length' => Arr::get($track, 'duration_ms', 0),
                    'image' => Arr::get($track, 'album.images.0.url'),
                ]);
                $totalSaved++;
            }

            $playlist->songs()->saveMany($songs);
            $loopIndex++;
        } while (self::INSERT_MAX > $totalSaved);

        DB::commit();
    }

    private function getGlobalUser(): User
    {
        return User::query()
            ->where('spotify_id', config('services.spotify.playlist_owner'))
            ->firstOrFail();
    }

    public function updateGlobalPlaylist(): void
    {
        $globalUser = $this->getGlobalUser();
        $this->setUser($globalUser);

        $playlist = Playlist::query()->where('name', 'global')->latest()->first();
        if (!$playlist) {
            $playlist = $globalUser->playlists()->create(['name' => 'global']);
        }

        if ($playlist->songs()->exists()) {
            $playlist->songs()->chunk(
                self::GLOBAL_PER_PAGE,
                function (Collection $songs) use (&$totalAdded, &$playlist): void {
                    $this->spotifyService->request(
                        'delete',
                        'playlists/' . config('services.spotify.playlist_id') . '/tracks',
                        [
                            'tracks' => $songs->map(
                                fn(Song $song): array => ['uri' => 'spotify:track:' . $song->spotify_id]
                            )->all(),
                        ],
                    );
                }
            );
            $playlist->songs()->delete();
        }

        $totalAdded = 0;
        Song::query()
            ->select([
                DB::raw('max(spotify_id) as spotify_id'),
                DB::raw('max(spotify_artist_id) as spotify_artist_id'),
                DB::raw('max(artist) as artist'),
                DB::raw('max(name) as name'),
                DB::raw('max(length) as length'),
                DB::raw('max(image) as image'),
                DB::raw('ROUND(EXP(SUM(LOG(playlist_position)))) as playlist_position'),
            ])
            ->groupBy('spotify_id')
            ->orderByRaw('playlist_position desc')
            ->chunk(self::GLOBAL_PER_PAGE, function (Collection $songs) use (&$totalAdded, &$playlist): bool {
                $uris = $songs->map(fn(Song $song): string => 'spotify:track:' . $song->spotify_id);
                $this->spotifyService->request(
                    'post',
                    'playlists/' . config('services.spotify.playlist_id') . '/tracks',
                    ['uris' => $uris->all()],
                );
                $songs = $songs->map(fn(Song $song): Song => (new Song())->fill($song->getAttributes()));
                $playlist->songs()->saveMany($songs->all());
                $totalAdded++;

                if ($totalAdded >= self::GLOBAL_MAX) {
                    return false;
                }

                return true;
            });
    }
}
