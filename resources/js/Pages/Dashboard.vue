<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    announcements: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="row">
            <div class="col-12">
                <h1>Dashboard</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Announcements</div>
                    <div class="card-body">
                        <div
                            v-for="announcement in announcements"
                            :key="announcement.id"
                            class="mb-3 pb-3 border-bottom"
                        >
                            <h5>{{ announcement.title }}</h5>
                            <p class="text-muted small">
                                {{ announcement.created_at }}
                            </p>
                            <div v-html="announcement.content"></div>
                        </div>
                        <p v-if="!announcements.length" class="text-muted">
                            No announcements yet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
