<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: { type: Object, required: true },
    roles: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    store: props.user.store,
    role: [...props.user.role_ids],
});

function submit() {
    form.post(`/admin/users/${props.user.id}/edit`);
}
</script>

<template>
    <Head :title="`Edit ${user.name}`" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit User</h1>
            <Link href="/admin/users">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="640" data-testid="user-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        data-testid="user-name"
                    />
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        type="email"
                        :error-messages="form.errors.email"
                        data-testid="user-email"
                    />
                    <v-text-field
                        v-model="form.password"
                        label="New Password (leave blank to keep current)"
                        type="password"
                        :error-messages="form.errors.password"
                        data-testid="user-password"
                    />
                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Confirm New Password"
                        type="password"
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
                        data-testid="user-roles"
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
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
