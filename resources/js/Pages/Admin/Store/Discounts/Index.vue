<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    discounts: { type: Array, default: () => [] },
    typeOptions: { type: Array, default: () => [] },
    filterOptions: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Type', key: 'type' },
    { title: 'Filter', key: 'filter' },
    { title: 'Amount', key: 'amount' },
    { title: 'Approval?', key: 'approval' },
    { title: 'Redeemable?', key: 'redeemable' },
    { title: 'Points', key: 'value' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

const dialog = ref(false);

const form = useForm({
    name: '',
    type: 'percent',
    filter: 'none',
    amount: '',
    approval: false,
    redeemable: false,
    value: '',
});

function submit() {
    form.post('/admin/store/discounts/create', {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
        },
    });
}
</script>

<template>
    <Head title="Discounts" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Discounts</h1>
            <v-btn
                color="primary"
                data-testid="discounts-create-button"
                @click="dialog = true"
            >
                <v-icon start icon="mdi-plus" /> New Discount
            </v-btn>
        </div>

        <v-card data-testid="discounts-table-card">
            <v-data-table
                :headers="headers"
                :items="discounts"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.amount="{ item }">
                    <span v-if="item.type === 'percent'">{{ item.amount }}%</span>
                    <span v-else>${{ Number(item.amount).toFixed(2) }}</span>
                </template>
                <template #item.approval="{ item }">
                    <v-icon
                        :icon="item.approval ? 'mdi-check-circle' : 'mdi-close-circle'"
                        :color="item.approval ? 'success' : 'grey'"
                    />
                </template>
                <template #item.redeemable="{ item }">
                    <v-icon
                        :icon="item.redeemable ? 'mdi-check-circle' : 'mdi-close-circle'"
                        :color="item.redeemable ? 'success' : 'grey'"
                    />
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/discounts/${item.id}/edit`">
                        <v-btn
                            size="small"
                            variant="text"
                            icon="mdi-pencil"
                            :data-testid="`discount-edit-${item.id}`"
                        />
                    </Link>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" max-width="600" data-testid="discount-dialog">
            <v-card>
                <v-card-title>New Discount</v-card-title>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.name"
                            label="Name"
                            :error-messages="form.errors.name"
                            required
                            data-testid="discount-name"
                        />
                        <v-select
                            v-model="form.type"
                            :items="typeOptions"
                            item-title="title"
                            item-value="value"
                            label="Type"
                            :error-messages="form.errors.type"
                            data-testid="discount-type"
                        />
                        <v-select
                            v-model="form.filter"
                            :items="filterOptions"
                            item-title="title"
                            item-value="value"
                            label="Filter"
                            :error-messages="form.errors.filter"
                            data-testid="discount-filter"
                        />
                        <v-text-field
                            v-model="form.amount"
                            label="Amount"
                            type="number"
                            step="0.01"
                            :error-messages="form.errors.amount"
                            data-testid="discount-amount"
                        />
                        <v-checkbox
                            v-model="form.approval"
                            label="Requires approval"
                            hide-details
                            data-testid="discount-approval"
                        />
                        <v-checkbox
                            v-model="form.redeemable"
                            label="Redeemable with points"
                            hide-details
                            data-testid="discount-redeemable"
                        />
                        <v-text-field
                            v-if="form.redeemable"
                            v-model="form.value"
                            label="Point Value"
                            type="number"
                            :error-messages="form.errors.value"
                            class="mt-2"
                            data-testid="discount-value"
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn variant="text" @click="dialog = false">Cancel</v-btn>
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        data-testid="discount-submit"
                        @click="submit"
                    >
                        Create
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
