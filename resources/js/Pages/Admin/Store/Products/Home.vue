<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const actions = [
    { href: '/admin/store/products/index', title: 'All Products', icon: 'mdi-format-list-bulleted' },
    { href: '/admin/store/products/create', title: 'New Product', icon: 'mdi-plus-circle' },
    { href: '/admin/store/products/redline', title: 'Redline Alerts', icon: 'mdi-alert-circle' },
];
</script>

<template>
    <Head title="Products" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Products</h1>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <Link v-for="action in actions" :key="action.href" :href="action.href">
                <v-card hover :data-testid="`products-card-${action.title.toLowerCase().replace(/\s/g, '-')}`">
                    <v-card-text class="flex items-center gap-3">
                        <v-icon :icon="action.icon" size="large" color="primary" />
                        <span class="text-base font-medium">{{ action.title }}</span>
                    </v-card-text>
                </v-card>
            </Link>
        </div>
    </AppLayout>
</template>
