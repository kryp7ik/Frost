<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    users: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
});
</script>

<template>
    <Head title="Schedule" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Schedule</h1>

        <v-card data-testid="schedule-card">
            <v-card-text>
                <p class="mb-4 text-sm text-gray-600">
                    A full calendar-backed schedule editor is planned but not yet migrated.
                    Below is the raw seeded employee roster so you can see shift assignments.
                </p>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="rounded-md border border-gray-200 p-3 text-sm"
                        :data-testid="`employee-${user.id}`"
                    >
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-block h-3 w-3 rounded-full"
                                :style="{ backgroundColor: user.color || '#999' }"
                            />
                            <span class="font-semibold">{{ user.name }}</span>
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                            Store: {{ stores.find((s) => s.id === user.store)?.name || user.store || '—' }}
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
