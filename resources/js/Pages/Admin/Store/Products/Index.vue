<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    products: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'SKU', key: 'sku' },
    { title: 'Category', key: 'category' },
    { title: 'Cost', key: 'cost' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];
</script>

<template>
    <Head title="Products" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Products</h1>
            <Link href="/admin/store/products/create">
                <v-btn color="primary" data-testid="products-create-button">
                    <v-icon start icon="mdi-plus" /> New Product
                </v-btn>
            </Link>
        </div>

        <v-card data-testid="products-table-card">
            <v-data-table
                :headers="headers"
                :items="products"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.cost="{ item }">${{ Number(item.cost).toFixed(2) }}</template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/products/${item.id}/show`">
                        <v-btn size="small" variant="text" icon="mdi-eye" />
                    </Link>
                    <Link :href="`/admin/store/products/${item.id}/edit`">
                        <v-btn size="small" variant="text" icon="mdi-pencil" />
                    </Link>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
