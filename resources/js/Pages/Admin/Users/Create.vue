<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    roles: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    store: null,
    role: [],
});

function submit() {
    form.post('/admin/users/create');
}
</script>

<template>
    <Head title="New User" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New User</h1>
            <Link href="/admin/users">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="640" data-testid="user-form-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        required
                        data-testid="user-name"
                    />
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        type="email"
                        :error-messages="form.errors.email"
                        required
                        data-testid="user-email"
                    />
                    <v-text-field
                        v-model="form.password"
                        label="Password"
                        type="password"
                        :error-messages="form.errors.password"
                        required
                        data-testid="user-password"
                    />
                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Confirm Password"
                        type="password"
                        required
                        data-testid="user-password-confirm"
                    />
                    <v-select
                        v-model="form.store"
                        :items="stores"
                        item-title="name"
                        item-value="id"
                        label="Store"
                        :error-messages="form.errors.store"
                        data-testid="user-store"
                    />
                    <v-select
                        v-model="form.role"
                        :items="roles"
                        item-title="display_name"
                        item-value="id"
                        label="Roles"
                        multiple
                        chips
                        :error-messages="form.errors.role"
                        data-testid="user-role"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/admin/users">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="user-submit"
                        >
                            Create
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
