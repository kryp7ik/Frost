<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    discount: { type: Object, required: true },
    typeOptions: { type: Array, default: () => [] },
    filterOptions: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.discount.name,
    type: props.discount.type,
    filter: props.discount.filter,
    amount: props.discount.amount,
    approval: props.discount.approval,
    redeemable: props.discount.redeemable,
    value: props.discount.value,
});

function submit() {
    form.post(`/admin/store/discounts/${props.discount.id}/edit`);
}
</script>

<template>
    <Head :title="`Edit ${discount.name}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Discount</h1>
            <Link href="/admin/store/discounts">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="600" data-testid="discount-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        data-testid="discount-name"
                    />
                    <v-select
                        v-model="form.type"
                        :items="typeOptions"
                        item-title="title"
                        item-value="value"
                        label="Type"
                        data-testid="discount-type"
                    />
                    <v-select
                        v-model="form.filter"
                        :items="filterOptions"
                        item-title="title"
                        item-value="value"
                        label="Filter"
                        data-testid="discount-filter"
                    />
                    <v-text-field
                        v-model="form.amount"
                        label="Amount"
                        type="number"
                        step="0.01"
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
                        class="mt-2"
                        data-testid="discount-value"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/store/discounts">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="discount-submit"
                        >
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
