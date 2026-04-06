<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const sections = [
    { href: '/admin/store/products/index', title: 'Products', icon: 'mdi-tag-multiple' },
    { href: '/admin/store/shipments', title: 'Shipments', icon: 'mdi-truck-delivery-outline' },
    { href: '/admin/store/transfers', title: 'Transfers', icon: 'mdi-swap-horizontal' },
    { href: '/admin/store/inventory/create', title: 'Inventory', icon: 'mdi-database-outline' },
    { href: '/admin/store/discounts', title: 'Discounts', icon: 'mdi-sale' },
    { href: '/admin/store/recipes', title: 'Recipes', icon: 'mdi-tea' },
    { href: '/admin/store/ingredients', title: 'Ingredients', icon: 'mdi-flask-outline' },
    { href: '/admin/store/report/sales', title: 'Reports', icon: 'mdi-chart-line' },
    { href: '/admin/store/touch', title: 'Touch', icon: 'mdi-gesture-tap' },
    { href: '/admin/users', title: 'Users', icon: 'mdi-account-group-outline' },
    { href: '/admin/roles', title: 'Roles', icon: 'mdi-shield-key-outline' },
];
</script>

<template>
    <Head title="Admin" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Admin</h1>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            <Link
                v-for="section in sections"
                :key="section.href"
                :href="section.href"
                class="block"
            >
                <v-card hover :data-testid="`admin-card-${section.title.toLowerCase()}`">
                    <v-card-text class="flex items-center gap-3">
                        <v-icon :icon="section.icon" size="large" color="primary" />
                        <span class="text-base font-medium">{{ section.title }}</span>
                    </v-card-text>
                </v-card>
            </Link>
        </div>
    </AppLayout>
</template>
