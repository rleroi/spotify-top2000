<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    playlist?: Object;
}>();

const form = useForm({});

const user = usePage().props.auth.user;

console.log(user);

function updateGlobalPlaylist() {
    form.post('/api/updateGlobalPlaylist', {
        preserveScroll: true,
        onSuccess: () => router.reload(),
    });
}

function openPlaylist() {
    window.open(
        'https://open.spotify.com/playlist/7fMFfyvQWXW0c2utSUn5tD',
        '_blank',
    );
}
</script>

<template>
    <Head :title="'Global Playlist'" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                {{ 'Global Playlist' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6 flex gap-6">
                            <secondary-button @click.prevent="openPlaylist">
                                <img src="/img/spotify.png" class="h-9" />
                                <span class="ml-4">Listen to Playlist</span>
                            </secondary-button>
                            <primary-button
                                v-if="user.is_global_user"
                                :disabled="form.processing"
                                @click.prevent="updateGlobalPlaylist"
                            >
                                Refresh global playlist
                            </primary-button>
                        </div>

                        <table class="w-full">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Name</td>
                                    <td>Artist</td>
                                    <td class="hidden sm:block"></td>
                                    <td>Score</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(song, i) in playlist?.songs || []"
                                    :key="song.spotify_id"
                                >
                                    <td>{{ i + 1 }}</td>
                                    <td>{{ song.name }}</td>
                                    <td>{{ song.artist }}</td>
                                    <td class="hidden min-w-12 sm:block px-2">
                                        <img
                                            class="h-12 object-cover"
                                            :src="song.image"
                                            alt="cover"
                                        />
                                    </td>
                                    <td class="text-right">
                                        {{ song.playlist_position }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
