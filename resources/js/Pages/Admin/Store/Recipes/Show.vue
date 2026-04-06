<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    recipe: { type: Object, required: true },
    availableIngredients: { type: Array, default: () => [] },
});

const addForm = useForm({
    recipe: props.recipe.id,
    ingredient: null,
    amount: 1,
});

function addIngredient() {
    addForm.post('/admin/store/recipes/add', {
        onSuccess: () => addForm.reset('ingredient', 'amount'),
    });
}

function removeIngredient(ingredientId) {
    router.get(`/admin/store/recipes/${props.recipe.id}/remove/${ingredientId}`);
}
</script>

<template>
    <Head :title="recipe.name" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">{{ recipe.name }}</h1>
            <Link href="/admin/store/recipes">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card class="mb-4" data-testid="recipe-details-card">
            <v-card-text>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <dt class="font-semibold">SKU</dt>
                    <dd>{{ recipe.sku }}</dd>
                    <dt class="font-semibold">Active</dt>
                    <dd>
                        <v-icon :icon="recipe.active ? 'mdi-check-circle' : 'mdi-close-circle'" :color="recipe.active ? 'success' : 'grey'" />
                    </dd>
                </dl>
            </v-card-text>
        </v-card>

        <v-card data-testid="recipe-ingredients-card">
            <v-card-title>Ingredients</v-card-title>
            <v-card-text>
                <v-list density="compact">
                    <v-list-item
                        v-for="ing in recipe.ingredients"
                        :key="ing.id"
                        :title="ing.name"
                        :subtitle="`Amount: ${ing.amount}`"
                    >
                        <template #append>
                            <v-btn
                                size="small"
                                variant="text"
                                color="error"
                                icon="mdi-delete"
                                @click="removeIngredient(ing.id)"
                            />
                        </template>
                    </v-list-item>
                    <v-list-item v-if="!recipe.ingredients.length">
                        <template #title>No ingredients yet.</template>
                    </v-list-item>
                </v-list>

                <v-divider class="my-3" />

                <div class="flex items-end gap-3">
                    <v-select
                        v-model="addForm.ingredient"
                        :items="availableIngredients"
                        item-title="name"
                        item-value="id"
                        label="Ingredient"
                        class="flex-1"
                        data-testid="recipe-add-ingredient"
                    />
                    <v-text-field
                        v-model.number="addForm.amount"
                        label="Amount"
                        type="number"
                        step="0.01"
                        style="max-width: 140px;"
                        data-testid="recipe-add-amount"
                    />
                    <v-btn
                        color="primary"
                        :loading="addForm.processing"
                        data-testid="recipe-add-submit"
                        @click="addIngredient"
                    >
                        <v-icon start icon="mdi-plus" /> Add
                    </v-btn>
                </div>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
