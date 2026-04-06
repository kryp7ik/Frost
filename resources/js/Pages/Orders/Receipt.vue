<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    order: { type: Object, required: true },
    change: { type: [String, Number], default: null },
});

const emailSending = ref(false);
const emailSent = ref(false);
const emailError = ref(false);

async function emailReceipt() {
    emailSending.value = true;
    emailSent.value = false;
    emailError.value = false;
    try {
        const { data } = await axios.post('/orders/email-receipt', { order: props.order.id });
        emailSent.value = data.status === 'success';
        emailError.value = data.status !== 'success';
    } catch {
        emailError.value = true;
    } finally {
        emailSending.value = false;
    }
}
</script>

<template>
    <Head :title="`Receipt #${order.id}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Receipt #{{ order.id }}</h1>
            <Link href="/orders/create">
                <v-btn color="primary">New Order</v-btn>
            </Link>
        </div>

        <v-card max-width="520" data-testid="receipt-card">
            <v-card-text>
                <v-alert
                    v-if="change"
                    type="info"
                    variant="tonal"
                    class="mb-3"
                    data-testid="receipt-change"
                >
                    Change due: ${{ change }}
                </v-alert>

                <p class="mb-3 text-sm text-gray-500">
                    {{ order.created_at }}
                    <span v-if="order.customer"> &bull; {{ order.customer }}</span>
                </p>

                <v-list density="compact">
                    <v-list-item
                        v-for="(product, idx) in order.products"
                        :key="`p-${idx}`"
                        :title="`${product.name} × ${product.quantity}`"
                        :subtitle="`$${(product.price * product.quantity).toFixed(2)}`"
                    />
                    <v-list-item
                        v-for="(liquid, idx) in order.liquids"
                        :key="`l-${idx}`"
                        :title="liquid.recipe_name"
                        :subtitle="`$${liquid.price.toFixed(2)}`"
                    />
                </v-list>

                <v-divider class="my-3" />

                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between border-t pt-2 text-base font-semibold">
                        <dt>Total Paid</dt>
                        <dd>${{ order.total.toFixed(2) }}</dd>
                    </div>
                </dl>

                <div class="mt-4 flex gap-2">
                    <v-btn variant="tonal" @click="() => window.print()">
                        <v-icon start icon="mdi-printer" /> Print
                    </v-btn>
                    <v-btn
                        variant="tonal"
                        :loading="emailSending"
                        :color="emailSent ? 'success' : emailError ? 'error' : undefined"
                        data-testid="receipt-email"
                        @click="emailReceipt"
                    >
                        <v-icon start :icon="emailSent ? 'mdi-check' : 'mdi-email'" />
                        {{ emailSent ? 'Sent' : emailError ? 'Failed' : 'Email Receipt' }}
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
