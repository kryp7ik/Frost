<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    ingredient: { type: Object, required: true },
});

const form = useForm({
    name: props.ingredient.name,
    vendor: props.ingredient.vendor,
});

function submit() {
    form.post(`/admin/store/ingredients/${props.ingredient.id}/edit`);
}
</script>

<template>
    <Head :title="`Edit ${ingredient.name}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Ingredient</h1>
            <Link href="/admin/store/ingredients">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="560" data-testid="ingredient-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        data-testid="ingredient-name"
                    />
                    <v-text-field
                        v-model="form.vendor"
                        label="Vendor"
                        :error-messages="form.errors.vendor"
                        data-testid="ingredient-vendor"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/store/ingredients">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="ingredient-submit"
                        >
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
