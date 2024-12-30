<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head } from '@inertiajs/vue3';

function playlist() {
    window.open(
        'https://open.spotify.com/playlist/7fMFfyvQWXW0c2utSUn5tD',
        '_blank',
    );
}

function login() {
    window.location.href = '/redirect';
}
</script>

<template>
    <Head title="Spotify Top 2000" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute left-0 top-0 h-full w-full"
            src="/img/background.jpg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#1ED760] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <img src="/img/logo.png" class="h-9" />
                    </div>
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <div
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#1ED760] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#1ED760]"
                        >
                            <div
                                class="relative flex items-center gap-6 lg:items-end"
                            >
                                <div
                                    id="docs-card-content"
                                    class="flex items-start gap-6 lg:flex-col"
                                >
                                    <div class="">
                                        <h2
                                            class="text-xl font-semibold text-black dark:text-white"
                                        >
                                            Spotify Top 2000
                                        </h2>

                                        <p class="mt-4 text-sm/relaxed">
                                            Sign in with your Spotify account to
                                            add your top tracks to our Top 2000
                                            Playlist.
                                        </p>
                                        <p class="mt-4 text-sm/relaxed">
                                            <primary-button
                                                @click.prevent="login"
                                            >
                                                <img
                                                    src="/img/spotify.png"
                                                    class="h-9"
                                                    alt=""
                                                />
                                                <span class="ml-4"
                                                    >Sign in with Spotify</span
                                                >
                                            </primary-button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#1ED760] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#1ED760]"
                        >
                            <div
                                class="relative flex items-center gap-6 lg:items-end"
                            >
                                <div
                                    id="docs-card-content"
                                    class="flex items-start gap-6 lg:flex-col"
                                >
                                    <div class="">
                                        <h2
                                            class="text-xl font-semibold text-black dark:text-white"
                                        >
                                            Get the Playlist
                                        </h2>

                                        <p class="mt-4 text-sm/relaxed">
                                            <secondary-button
                                                @click.prevent="playlist"
                                            >
                                                <img
                                                    src="/img/spotify.png"
                                                    class="h-9"
                                                />
                                                <span class="ml-4"
                                                    >Listen to Top 2000</span
                                                >
                                            </secondary-button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    &copy; 2025 - Cyberpunk Hippies
                </footer>
            </div>
        </div>
    </div>
</template>
