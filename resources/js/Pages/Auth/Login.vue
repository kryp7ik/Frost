<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/users/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <v-app>
        <v-main class="bg-gray-100">
            <v-container class="d-flex align-center justify-center" style="min-height: 100vh;">
                <v-card
                    class="pa-6"
                    elevation="4"
                    max-width="420"
                    width="100%"
                    data-testid="login-card"
                >
                    <v-card-title class="text-h5 mb-4">Log in to Frost</v-card-title>

                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.email"
                            label="Email"
                            type="email"
                            autocomplete="username"
                            :error-messages="form.errors.email"
                            required
                            autofocus
                            data-testid="login-email"
                        />

                        <v-text-field
                            v-model="form.password"
                            label="Password"
                            type="password"
                            autocomplete="current-password"
                            :error-messages="form.errors.password"
                            required
                            class="mt-2"
                            data-testid="login-password"
                        />

                        <v-checkbox
                            v-model="form.remember"
                            label="Remember me"
                            hide-details
                            class="mb-4"
                            data-testid="login-remember"
                        />

                        <v-btn
                            type="submit"
                            color="primary"
                            block
                            size="large"
                            :loading="form.processing"
                            :disabled="form.processing"
                            data-testid="login-submit"
                        >
                            Log in
                        </v-btn>
                    </v-form>
                </v-card>
            </v-container>
        </v-main>
    </v-app>
</template>
