<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    transfer: { type: Object, required: true },
});
</script>

<template>
    <Head :title="`Transfer #${transfer.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Transfer #{{ transfer.id }}</h1>
            <Link href="/admin/store/transfers">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card data-testid="transfer-show-card">
            <v-card-text>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <dt class="font-semibold">From</dt>
                    <dd>{{ transfer.from_store_name || `#${transfer.from_store}` }}</dd>
                    <dt class="font-semibold">To</dt>
                    <dd>{{ transfer.to_store_name || `#${transfer.to_store}` }}</dd>
                    <dt class="font-semibold">Status</dt>
                    <dd>
                        <v-chip
                            :color="transfer.received ? 'success' : 'warning'"
                            size="small"
                            variant="tonal"
                        >
                            {{ transfer.received ? 'Received' : 'In Transit' }}
                        </v-chip>
                    </dd>
                    <dt class="font-semibold">Created</dt>
                    <dd>{{ transfer.created_at }}</dd>
                </dl>

                <v-divider class="my-4" />

                <h2 class="mb-2 text-lg font-semibold">Contents</h2>
                <v-list density="compact">
                    <v-list-item
                        v-for="instance in transfer.instances"
                        :key="instance.id"
                        :title="`Instance #${instance.id}`"
                        :subtitle="`Qty: ${instance.quantity}${instance.received ? ' (received)' : ''}`"
                    />
                </v-list>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
