<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import DeleteUserForm from './Partials/DeleteUserForm.vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!--                <div
                                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                                >
                                    <UpdateProfileInformationForm
                                        :must-verify-email="mustVerifyEmail"
                                        :status="status"
                                        class="max-w-xl"
                                    />
                                </div>-->

                <!--                <div
                                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                                >
                                    <UpdatePasswordForm class="max-w-xl" />
                                </div>-->

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <section class="space-y-6">
                        <header>
                            <h2
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ user.name }}
                            </h2>
                        </header>
                        <img :src="user.avatar" alt="Avatar" class="w-72 object-cover rounded-full" />
                    </section>
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
