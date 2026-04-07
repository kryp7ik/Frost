<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    transfers: { type: Array, default: () => [] },
});

const headers = [
    { title: 'ID', key: 'id' },
    { title: 'From', key: 'from_store_name' },
    { title: 'To', key: 'to_store_name' },
    { title: 'Received', key: 'received' },
    { title: 'Created', key: 'created_at' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

function receiveTransfer(id) {
    if (!confirm('Mark this transfer as received?')) {
        return;
    }
    router.get(`/admin/store/transfers/${id}/receive`);
}
</script>

<template>
    <Head title="Transfers" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Transfers</h1>
            <Link href="/admin/store/transfers/create">
                <v-btn color="primary" data-testid="transfers-create-button">
                    <v-icon start icon="mdi-plus" /> New Transfer
                </v-btn>
            </Link>
        </div>

        <v-card data-testid="transfers-table-card">
            <v-data-table
                :headers="headers"
                :items="transfers"
                item-value="id"
                density="comfortable"
                :items-per-page="25"
            >
                <template #item.received="{ item }">
                    <v-chip
                        :color="item.received ? 'success' : 'warning'"
                        size="small"
                        variant="tonal"
                    >
                        {{ item.received ? 'Received' : 'In Transit' }}
                    </v-chip>
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/transfers/${item.id}/show`">
                        <v-btn
                            size="small"
                            variant="text"
                            icon="mdi-eye"
                            :data-testid="`transfer-view-${item.id}`"
                        />
                    </Link>
                    <v-btn
                        v-if="!item.received"
                        size="small"
                        variant="text"
                        color="success"
                        icon="mdi-check"
                        :data-testid="`transfer-receive-${item.id}`"
                        @click="receiveTransfer(item.id)"
                    />
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
