<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    data: { type: [Array, Object], default: () => [] },
    store: { type: [Number, String], default: null },
    stores: { type: Array, default: () => [] },
});

const selectedStore = ref(props.store);

function runReport() {
    router.get('/admin/store/report/inventory', { store: selectedStore.value }, {
        preserveState: true,
    });
}
</script>

<template>
    <Head title="Inventory Report" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Inventory Report</h1>

        <v-card class="mb-4" data-testid="inventory-filters-card">
            <v-card-text class="flex items-end gap-3">
                <v-select
                    v-model="selectedStore"
                    :items="stores"
                    item-title="name"
                    item-value="id"
                    label="Store"
                    clearable
                    class="flex-1"
                    data-testid="inventory-store"
                />
                <v-btn
                    color="primary"
                    data-testid="inventory-run"
                    @click="runReport"
                >
                    Run Report
                </v-btn>
            </v-card-text>
        </v-card>

        <v-card data-testid="inventory-results-card">
            <v-card-text>
                <pre class="overflow-x-auto text-xs">{{ data }}</pre>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
