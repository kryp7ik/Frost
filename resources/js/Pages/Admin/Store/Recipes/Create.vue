<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    ingredients: { type: Array, default: () => [] },
});

const form = useForm({
    name: '',
    sku: '',
});

function submit() {
    form.post('/admin/store/recipes/create');
}
</script>

<template>
    <Head title="New Recipe" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Recipe</h1>
            <Link href="/admin/store/recipes">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="560" data-testid="recipe-form-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        required
                        data-testid="recipe-name"
                    />
                    <v-text-field
                        v-model="form.sku"
                        label="SKU"
                        :error-messages="form.errors.sku"
                        data-testid="recipe-sku"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/store/recipes">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="recipe-submit"
                        >
                            Create
                        </v-btn>
                    </div>
                </v-form>
                <p class="mt-4 text-xs text-gray-500">
                    You can add ingredients to this recipe after creating it.
                </p>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
