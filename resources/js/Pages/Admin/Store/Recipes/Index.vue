<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    recipes: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'SKU', key: 'sku' },
    { title: 'Active', key: 'active' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];
</script>

<template>
    <Head title="Recipes" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Recipes</h1>
            <Link href="/admin/store/recipes/create">
                <v-btn color="primary" data-testid="recipes-create-button">
                    <v-icon start icon="mdi-plus" /> New Recipe
                </v-btn>
            </Link>
        </div>

        <v-card data-testid="recipes-table-card">
            <v-data-table
                :headers="headers"
                :items="recipes"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.active="{ item }">
                    <v-icon
                        :icon="item.active ? 'mdi-check-circle' : 'mdi-close-circle'"
                        :color="item.active ? 'success' : 'grey'"
                    />
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/recipes/${item.id}/show`">
                        <v-btn size="small" variant="text" icon="mdi-eye" />
                    </Link>
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
