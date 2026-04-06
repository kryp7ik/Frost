<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    instance: { type: Object, required: true },
});

const form = useForm({
    price: props.instance.price,
    stock: props.instance.stock,
    redline: props.instance.redline,
    active: props.instance.active,
});

function submit() {
    form.post(`/admin/store/products/instance/${props.instance.id}/edit`);
}
</script>

<template>
    <Head title="Edit Instance" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Product Instance</h1>
            <Link :href="`/admin/store/products/${instance.product_id}/show`">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="560" data-testid="instance-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field v-model="form.price" label="Price" type="number" step="0.01" prefix="$" data-testid="instance-price" />
                    <v-text-field v-model="form.stock" label="Stock" type="number" data-testid="instance-stock" />
                    <v-text-field v-model="form.redline" label="Redline" type="number" data-testid="instance-redline" />
                    <v-checkbox v-model="form.active" label="Active" data-testid="instance-active" />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link :href="`/admin/store/products/${instance.product_id}/show`">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="instance-submit"
                        >
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
