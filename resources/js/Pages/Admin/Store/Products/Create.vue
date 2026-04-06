<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    sku: '',
    category: '',
    cost: '',
});

function submit() {
    form.post('/admin/store/products/create');
}
</script>

<template>
    <Head title="New Product" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Product</h1>
            <Link href="/admin/store/products/index">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="640" data-testid="product-form-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        required
                        data-testid="product-name"
                    />
                    <v-text-field
                        v-model="form.sku"
                        label="SKU"
                        :error-messages="form.errors.sku"
                        data-testid="product-sku"
                    />
                    <v-text-field
                        v-model="form.category"
                        label="Category"
                        :error-messages="form.errors.category"
                        data-testid="product-category"
                    />
                    <v-text-field
                        v-model="form.cost"
                        label="Cost"
                        type="number"
                        step="0.01"
                        prefix="$"
                        :error-messages="form.errors.cost"
                        data-testid="product-cost"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/store/products/index">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="product-submit"
                        >
                            Create
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
