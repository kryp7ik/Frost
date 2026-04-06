<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    instances: { type: Array, default: () => [] },
});

const lines = ref([]);

const form = useForm({
    instances: {},
});

function addLine() {
    lines.value.push({ id: null, quantity: 1 });
}

function removeLine(index) {
    lines.value.splice(index, 1);
}

const hasLines = computed(() => lines.value.length > 0);

function submit() {
    form.instances = lines.value.reduce((acc, line) => {
        if (line.id && line.quantity > 0) {
            acc[line.id] = line.quantity;
        }
        return acc;
    }, {});
    form.post('/admin/store/shipments/create');
}
</script>

<template>
    <Head title="New Shipment" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Shipment</h1>
            <Link href="/admin/store/shipments">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card data-testid="shipment-form-card">
            <v-card-text>
                <p class="mb-3 text-sm text-gray-600">
                    Add one line per product instance you received in this shipment.
                </p>

                <v-btn
                    color="primary"
                    variant="tonal"
                    class="mb-4"
                    data-testid="shipment-add-line"
                    @click="addLine"
                >
                    <v-icon start icon="mdi-plus" /> Add Line
                </v-btn>

                <div
                    v-for="(line, idx) in lines"
                    :key="idx"
                    class="mb-3 flex items-start gap-3"
                    :data-testid="`shipment-line-${idx}`"
                >
                    <v-autocomplete
                        v-model="line.id"
                        :items="instances"
                        item-title="label"
                        item-value="id"
                        label="Product Instance"
                        class="flex-1"
                    />
                    <v-text-field
                        v-model.number="line.quantity"
                        label="Qty"
                        type="number"
                        min="1"
                        style="max-width: 120px;"
                    />
                    <v-btn
                        icon="mdi-delete"
                        variant="text"
                        color="error"
                        @click="removeLine(idx)"
                    />
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <Link href="/admin/store/shipments">
                        <v-btn variant="text">Cancel</v-btn>
                    </Link>
                    <v-btn
                        color="primary"
                        :disabled="!hasLines || form.processing"
                        :loading="form.processing"
                        data-testid="shipment-submit"
                        @click="submit"
                    >
                        Save Shipment
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
