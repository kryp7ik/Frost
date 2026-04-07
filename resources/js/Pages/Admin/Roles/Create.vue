<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    display_name: '',
    description: '',
});

function submit() {
    form.post('/admin/roles/create');
}
</script>

<template>
    <Head title="New Role" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Role</h1>
            <Link href="/admin/roles">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="640" data-testid="role-form-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name (machine)"
                        :error-messages="form.errors.name"
                        hint="e.g. manager, admin — lowercase, no spaces"
                        persistent-hint
                        required
                        data-testid="role-name"
                    />
                    <v-text-field
                        v-model="form.display_name"
                        label="Display Name"
                        :error-messages="form.errors.display_name"
                        required
                        class="mt-2"
                        data-testid="role-display-name"
                    />
                    <v-textarea
                        v-model="form.description"
                        label="Description"
                        rows="3"
                        :error-messages="form.errors.description"
                        class="mt-2"
                        data-testid="role-description"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/roles">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="role-submit"
                        >
                            Create
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
