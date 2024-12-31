<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    artists?: Object;
}>();
</script>

<template>
    <Head :title="'Artists'" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                {{ 'Artists' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>Artist</td>
                                    <td>Score</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(artist, i) in artists || []"
                                    :key="artist.spotify_artist_id"
                                >
                                    <td>{{ i + 1 }}</td>
                                    <td>{{ artist.artist }}</td>
                                    <td>
                                        {{ artist.playlist_position }}
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
