<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

import { computed } from 'vue';

const props = defineProps({
    announcements: {
        type: Object,
        default: () => ({ sticky: [], standard: [] }),
    },
    shifts: {
        type: [Array, Object],
        default: () => [],
    },
    dashboardUser: {
        type: Object,
        default: null,
    },
});

const visibleAnnouncements = computed(() => [
    ...(props.announcements?.sticky || []),
    ...(props.announcements?.standard || []),
]);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Dashboard</h1>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <v-card data-testid="announcements-card">
                    <v-card-title class="d-flex align-center">
                        <v-icon class="mr-2" icon="mdi-bullhorn-outline" />
                        Announcements
                    </v-card-title>
                    <v-divider />
                    <v-card-text>
                        <div
                            v-for="announcement in visibleAnnouncements"
                            :key="announcement.id"
                            class="mb-3 border-b border-gray-200 pb-3 last:border-0 last:pb-0"
                            :data-testid="`announcement-${announcement.id}`"
                        >
                            <h3 class="text-lg font-semibold">
                                <v-icon
                                    v-if="announcement.sticky"
                                    class="mr-1 text-amber-500"
                                    icon="mdi-pin"
                                    size="small"
                                />
                                {{ announcement.title }}
                            </h3>
                            <p class="text-xs text-gray-500">{{ announcement.created_at }}</p>
                            <div class="quill-content mt-2 text-sm" v-html="announcement.content" />
                        </div>
                        <p v-if="!visibleAnnouncements.length" class="text-sm text-gray-500">
                            No announcements yet.
                        </p>
                    </v-card-text>
                </v-card>
            </div>

            <div>
                <v-card data-testid="shifts-card">
                    <v-card-title class="d-flex align-center">
                        <v-icon class="mr-2" icon="mdi-calendar-week-outline" />
                        This Week
                    </v-card-title>
                    <v-divider />
                    <v-card-text>
                        <p v-if="!shifts || (Array.isArray(shifts) && !shifts.length)" class="text-sm text-gray-500">
                            No shifts scheduled.
                        </p>
                        <ul v-else class="space-y-2">
                            <li
                                v-for="(shift, idx) in shifts"
                                :key="shift.id || idx"
                                class="flex items-center justify-between text-sm"
                            >
                                <span>{{ shift.date || shift.clock_in }}</span>
                                <span class="text-gray-500">{{ shift.hours || '' }}</span>
                            </li>
                        </ul>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AppLayout>
</template>
