<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    customer: { type: Object, required: true },
    orders: { type: Array, default: () => [] },
});

const orderHeaders = [
    { title: '#', key: 'id' },
    { title: 'Date', key: 'created_at' },
    { title: 'Total', key: 'total' },
    { title: 'Status', key: 'complete' },
];
</script>

<template>
    <Head :title="customer.name || `Customer #${customer.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                {{ customer.name || `Customer #${customer.id}` }}
            </h1>
            <Link href="/customers">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <v-card data-testid="customer-details-card">
                <v-card-title>Details</v-card-title>
                <v-card-text>
                    <dl class="grid grid-cols-2 gap-2 text-sm">
                        <dt class="font-semibold">Phone</dt>
                        <dd>{{ customer.phone || '—' }}</dd>
                        <dt class="font-semibold">Email</dt>
                        <dd>{{ customer.email || '—' }}</dd>
                        <dt class="font-semibold">Points</dt>
                        <dd>{{ customer.points ?? 0 }}</dd>
                        <dt class="font-semibold">Preferred</dt>
                        <dd>{{ customer.preferred ? 'Yes' : 'No' }}</dd>
                    </dl>
                </v-card-text>
            </v-card>

            <v-card class="lg:col-span-2" data-testid="customer-orders-card">
                <v-card-title>Order History</v-card-title>
                <v-data-table
                    :headers="orderHeaders"
                    :items="orders"
                    item-value="id"
                    density="comfortable"
                >
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
                </v-data-table>
                <v-card-text v-if="!orders.length" class="text-center text-sm text-gray-500">
                    No orders yet.
                </v-card-text>
            </v-card>
        </div>
    </AppLayout>
</template>
