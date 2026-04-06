<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    roles: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Display Name', key: 'display_name' },
    { title: 'Description', key: 'description' },
];
</script>

<template>
    <Head title="Roles" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Roles</h1>
            <Link href="/admin/roles/create">
                <v-btn color="primary" data-testid="roles-create-button">
                    <v-icon start icon="mdi-plus" /> New Role
                </v-btn>
            </Link>
        </div>

        <v-card data-testid="roles-table-card">
            <v-data-table
                :headers="headers"
                :items="roles"
                item-value="id"
                density="comfortable"
            />
        </v-card>
    </AppLayout>
</template>
