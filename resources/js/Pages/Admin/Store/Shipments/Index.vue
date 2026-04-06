<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    shipments: { type: Array, default: () => [] },
});

const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Store', key: 'store_name' },
    { title: 'Items', key: 'instance_count' },
    { title: 'Created', key: 'created_at' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];
</script>

<template>
    <Head title="Shipments" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Shipments</h1>
            <Link href="/admin/store/shipments/create">
                <v-btn color="primary" data-testid="shipments-create-button">
                    <v-icon start icon="mdi-plus" /> New Shipment
                </v-btn>
            </Link>
        </div>

        <v-card data-testid="shipments-table-card">
            <v-data-table
                :headers="headers"
                :items="shipments"
                item-value="id"
                density="comfortable"
                :items-per-page="25"
            >
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/shipments/${item.id}/show`">
                        <v-btn
                            size="small"
                            variant="text"
                            icon="mdi-eye"
                            :data-testid="`shipment-view-${item.id}`"
                        />
                    </Link>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
