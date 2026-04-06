<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    content: '',
    type: 'general',
    sticky: false,
});

function submit() {
    form.post('/announcements/create');
}
</script>

<template>
    <Head title="New Announcement" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">New Announcement</h1>
            <Link href="/announcements">
                <v-btn variant="text"><v-icon start icon="mdi-arrow-left" /> Back</v-btn>
            </Link>
        </div>

        <v-card max-width="720" data-testid="announcement-form-card">
            <v-card-text>
                <v-form @submit.prevent="submit">
                    <v-text-field
                        v-model="form.title"
                        label="Title"
                        :error-messages="form.errors.title"
                        required
                        data-testid="announcement-title"
                    />
                    <v-select
                        v-model="form.type"
                        :items="[
                            { title: 'General', value: 'general' },
                            { title: 'Important', value: 'important' },
                            { title: 'Event', value: 'event' },
                        ]"
                        label="Type"
                        data-testid="announcement-type"
                    />
                    <v-textarea
                        v-model="form.content"
                        label="Content"
                        rows="8"
                        :error-messages="form.errors.content"
                        data-testid="announcement-content"
                    />
                    <v-checkbox
                        v-model="form.sticky"
                        label="Pin to top"
                        hide-details
                        data-testid="announcement-sticky"
                    />

                    <div class="mt-4 flex justify-end gap-2">
                        <Link href="/announcements">
                            <v-btn variant="text">Cancel</v-btn>
                        </Link>
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="form.processing"
                            data-testid="announcement-submit"
                        >
                            Post
                        </v-btn>
                    </div>
                </v-form>
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
