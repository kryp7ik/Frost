<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    instances: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
});

const lines = ref([]);

const form = useForm({
    from_store: null,
    to_store: null,
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
    form.post('/admin/store/transfers/create');
}
</script>

<template>
    <Head title="New Transfer" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Transfer</h1>
            <Link href="/admin/store/transfers">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card data-testid="transfer-form-card">
            <v-card-text>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <v-select
                        v-model="form.from_store"
                        :items="stores"
                        item-title="name"
                        item-value="id"
                        label="From Store"
                        :error-messages="form.errors.from_store"
                        data-testid="transfer-from"
                    />
                    <v-select
                        v-model="form.to_store"
                        :items="stores"
                        item-title="name"
                        item-value="id"
                        label="To Store"
                        :error-messages="form.errors.to_store"
                        data-testid="transfer-to"
                    />
                </div>

                <v-btn
                    color="primary"
                    variant="tonal"
                    class="my-4"
                    data-testid="transfer-add-line"
                    @click="addLine"
                >
                    <v-icon start icon="mdi-plus" /> Add Line
                </v-btn>

                <div
                    v-for="(line, idx) in lines"
                    :key="idx"
                    class="mb-3 flex items-start gap-3"
                    :data-testid="`transfer-line-${idx}`"
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
                    <Link href="/admin/store/transfers">
                        <v-btn variant="text">Cancel</v-btn>
                    </Link>
                    <v-btn
                        color="primary"
                        :disabled="!hasLines || form.processing"
                        :loading="form.processing"
                        data-testid="transfer-submit"
                        @click="submit"
                    >
                        Save Transfer
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
