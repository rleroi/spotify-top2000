<script setup lang="ts">
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps<{
    playlist?: Object;
}>();

const form = useForm({});

function insertTopTracks() {
    form.post('/api/insertTopTracks', {
        preserveScroll: true,
        onSuccess: () => router.reload(),
    });
}
</script>

<template>
    <Head :title="playlist?.name || 'Your Top 100'" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                {{ playlist?.name || 'Your Top 100' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <secondary-button
                            :disabled="form.processing"
                            class="mb-6"
                            @click.prevent="insertTopTracks"
                        >
                            Refresh your top tracks
                        </secondary-button>

                        <table class="w-full">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Name</td>
                                    <td>Artist</td>
                                    <td></td>
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
                                    <td class="min-w-12 pl-2">
                                        <img
                                            class="h-12 w-12 object-cover"
                                            :src="song.image"
                                            alt="cover"
                                        />
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
