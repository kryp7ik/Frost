<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: { type: Object, required: true },
});

const form = useForm({
    name: props.product.name,
    sku: props.product.sku,
    category: props.product.category,
    cost: props.product.cost,
});

function submit() {
    form.post(`/admin/store/products/${props.product.id}/edit`);
}
</script>

<template>
    <Head :title="`Edit ${product.name}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Product</h1>
            <Link :href="`/admin/store/products/${product.id}/show`">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="640" data-testid="product-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field v-model="form.name" label="Name" data-testid="product-name" />
                    <v-text-field v-model="form.sku" label="SKU" data-testid="product-sku" />
                    <v-text-field v-model="form.category" label="Category" data-testid="product-category" />
                    <v-text-field
                        v-model="form.cost"
                        label="Cost"
                        type="number"
                        step="0.01"
                        prefix="$"
                        data-testid="product-cost"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link :href="`/admin/store/products/${product.id}/show`">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="product-submit"
                        >
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
