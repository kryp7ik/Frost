<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    groups: { type: Array, default: () => [] },
});

const counts = reactive({});

const form = useForm({
    counts: {},
});

function submit() {
    form.counts = { ...counts };
    form.post('/admin/store/inventory/create');
}
</script>

<template>
    <Head title="Inventory Count" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Inventory Count</h1>
            <Link href="/">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Dashboard</v-btn>
            </Link>
        </div>

        <v-card data-testid="inventory-card">
            <v-card-text>
                <p class="mb-4 text-sm text-gray-600">
                    Enter current counts for each product instance in your store.
                </p>

                <v-form @submit.prevent="submit">
                    <div
                        v-for="group in groups"
                        :key="group.product_name"
                        class="mb-6"
                        :data-testid="`inventory-group-${group.product_name}`"
                    >
                        <h3 class="mb-2 text-lg font-semibold">{{ group.product_name }}</h3>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                            <v-text-field
                                v-for="instance in group.instances"
                                :key="instance.id"
                                v-model.number="counts[instance.id]"
                                :label="instance.label || `Instance #${instance.id}`"
                                type="number"
                                min="0"
                                :data-testid="`inventory-count-${instance.id}`"
                            />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="inventory-submit"
                        >
                            Submit Count
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
