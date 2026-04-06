<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Warning" />
    <AppLayout>
        <v-alert type="warning" variant="tonal" data-testid="schedule-warning">
            You are not currently clocked in. Please clock in before accessing this page.
        </v-alert>
    </AppLayout>
</template>
