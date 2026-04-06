<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    data: { type: Object, default: () => ({}) },
    filters: { type: Object, default: () => ({}) },
    stores: { type: Array, default: () => [] },
});

const form = reactive({
    start: props.filters.start || '',
    end: props.filters.end || '',
    store: props.filters.store || null,
    type: props.filters.type || 'detailed',
});

function runReport() {
    router.get('/admin/store/report/sales', form, {
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Sales Report" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Sales Report</h1>

        <v-card class="mb-4" data-testid="sales-filters-card">
            <v-card-text>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                    <v-text-field
                        v-model="form.start"
                        label="Start Date"
                        type="date"
                        data-testid="sales-start"
                    />
                    <v-text-field
                        v-model="form.end"
                        label="End Date"
                        type="date"
                        data-testid="sales-end"
                    />
                    <v-select
                        v-model="form.store"
                        :items="stores"
                        item-title="name"
                        item-value="id"
                        label="Store"
                        clearable
                        data-testid="sales-store"
                    />
                    <v-select
                        v-model="form.type"
                        :items="[
                            { title: 'Detailed', value: 'detailed' },
                            { title: 'Minimal', value: 'minimal' },
                        ]"
                        label="Type"
                        data-testid="sales-type"
                    />
                </div>
                <div class="mt-3 flex justify-end">
                    <v-btn
                        color="primary"
                        data-testid="sales-run"
                        @click="runReport"
                    >
                        Run Report
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>

        <v-card data-testid="sales-results-card">
            <v-card-text>
                <pre class="overflow-x-auto text-xs">{{ data }}</pre>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
