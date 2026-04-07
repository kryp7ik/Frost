<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    orders: { type: Array, default: () => [] },
    date: { type: String, default: '' },
});

const selectedDate = ref(props.date);

const headers = [
    { title: '#', key: 'id' },
    { title: 'Customer', key: 'customer_name' },
    { title: 'Total', key: 'total' },
    { title: 'Status', key: 'complete' },
    { title: 'Created', key: 'created_at' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

function filterByDate() {
    router.get('/orders', { start: selectedDate.value });
}
</script>

<template>
    <Head title="Orders" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Orders</h1>
            <div class="flex items-center gap-2">
                <v-text-field
                    v-model="selectedDate"
                    type="date"
                    hide-details
                    density="comfortable"
                    style="max-width: 180px;"
                    data-testid="orders-date"
                    @update:model-value="filterByDate"
                />
                <Link href="/orders/create">
                    <v-btn color="primary" data-testid="orders-create-button">
                        <v-icon start icon="mdi-plus" /> New Order
                    </v-btn>
                </Link>
            </div>
        </div>

        <v-card data-testid="orders-table-card">
            <v-data-table
                :headers="headers"
                :items="orders"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.customer_name="{ item }">
                    {{ item.customer_name || 'Walk-in' }}
                </template>
                <template #item.total="{ item }">${{ Number(item.total).toFixed(2) }}</template>
                <template #item.complete="{ item }">
                    <v-chip
                        :color="item.complete ? 'success' : 'warning'"
                        size="small"
                        variant="tonal"
                    >
                        {{ item.complete ? 'Complete' : 'Open' }}
                    </v-chip>
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/orders/${item.id}/show`">
                        <v-btn size="small" variant="text" icon="mdi-eye" :data-testid="`order-view-${item.id}`" />
                    </Link>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
