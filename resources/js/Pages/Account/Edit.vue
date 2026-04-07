<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: { type: Object, required: true },
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/account/edit');
}
</script>

<template>
    <Head title="Account Settings" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Account Settings</h1>
            <Link href="/">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Dashboard</v-btn>
            </Link>
        </div>

        <v-card max-width="560" data-testid="account-edit-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.name"
                        label="Name"
                        :error-messages="form.errors.name"
                        data-testid="account-name"
                    />
                    <v-text-field
                        v-model="form.email"
                        label="Email"
                        type="email"
                        :error-messages="form.errors.email"
                        data-testid="account-email"
                    />
                    <v-text-field
                        v-model="form.password"
                        label="New Password (leave blank to keep current)"
                        type="password"
                        :error-messages="form.errors.password"
                        data-testid="account-password"
                    />
                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Confirm New Password"
                        type="password"
                        data-testid="account-password-confirm"
                    />

                    <div class="mt-4 flex justify-end">
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="account-submit"
                        >
                            Save
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
