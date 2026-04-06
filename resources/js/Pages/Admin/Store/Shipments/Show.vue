<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    shipment: { type: Object, required: true },
});
</script>

<template>
    <Head :title="`Shipment #${shipment.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Shipment #{{ shipment.id }}</h1>
            <Link href="/admin/store/shipments">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card data-testid="shipment-show-card">
            <v-card-text>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <dt class="font-semibold">Store</dt>
                    <dd>{{ shipment.store_name || `#${shipment.store}` }}</dd>
                    <dt class="font-semibold">Created</dt>
                    <dd>{{ shipment.created_at }}</dd>
                    <dt class="font-semibold">Items</dt>
                    <dd>{{ shipment.instances.length }}</dd>
                </dl>

                <v-divider class="my-4" />

                <h2 class="mb-2 text-lg font-semibold">Contents</h2>
                <v-list density="compact">
                    <v-list-item
                        v-for="instance in shipment.instances"
                        :key="instance.id"
                        :title="`Instance #${instance.id}`"
                        :subtitle="`Qty: ${instance.quantity}`"
                    />
                </v-list>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
