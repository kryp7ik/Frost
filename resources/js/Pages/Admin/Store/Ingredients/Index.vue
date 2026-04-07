<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    ingredients: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Vendor', key: 'vendor' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

const dialog = ref(false);

const form = useForm({
    name: '',
    vendor: '',
});

function submit() {
    form.post('/admin/store/ingredients/create', {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
        },
    });
}
</script>

<template>
    <Head title="Ingredients" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Ingredients</h1>
            <v-btn
                color="primary"
                data-testid="ingredients-create-button"
                @click="dialog = true"
            >
                <v-icon start icon="mdi-plus" /> New Ingredient
            </v-btn>
        </div>

        <v-card data-testid="ingredients-table-card">
            <v-data-table
                :headers="headers"
                :items="ingredients"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.actions="{ item }">
                    <Link :href="`/admin/store/ingredients/${item.id}/edit`">
                        <v-btn
                            size="small"
                            variant="text"
                            icon="mdi-pencil"
                            :data-testid="`ingredient-edit-${item.id}`"
                        />
                    </Link>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" max-width="520" data-testid="ingredient-dialog">
            <v-card>
                <v-card-title>New Ingredient</v-card-title>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.name"
                            label="Name"
                            :error-messages="form.errors.name"
                            required
                            data-testid="ingredient-name"
                        />
                        <v-text-field
                            v-model="form.vendor"
                            label="Vendor"
                            :error-messages="form.errors.vendor"
                            required
                            data-testid="ingredient-vendor"
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn variant="text" @click="dialog = false">Cancel</v-btn>
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        data-testid="ingredient-submit"
                        @click="submit"
                    >
                        Create
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
