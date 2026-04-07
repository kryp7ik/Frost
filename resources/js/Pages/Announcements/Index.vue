<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    announcements: { type: Object, default: () => ({ sticky: [], standard: [] }) },
});

const all = computed(() => [
    ...(props.announcements?.sticky || []),
    ...(props.announcements?.standard || []),
]);

function deleteAnnouncement(id) {
    if (!confirm('Delete this announcement?')) {
        return;
    }
    router.get(`/announcements/${id}/delete`);
}
</script>

<template>
    <Head title="Announcements" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Announcements</h1>
            <Link href="/announcements/create">
                <v-btn color="primary" data-testid="announcements-create-button">
                    <v-icon start icon="mdi-plus" /> New Announcement
                </v-btn>
            </Link>
        </div>

        <div class="space-y-3" data-testid="announcements-list">
            <p v-if="!all.length" class="text-sm text-gray-500">No announcements yet.</p>
            <v-card
                v-for="announcement in all"
                :key="announcement.id"
                :data-testid="`announcement-${announcement.id}`"
            >
                <v-card-title>
                    <v-icon v-if="announcement.sticky" class="mr-2 text-amber-500" icon="mdi-pin" />
                    {{ announcement.title }}
                </v-card-title>
                <v-card-subtitle>{{ announcement.created_at }}</v-card-subtitle>
                <v-card-text>
                    <div class="quill-content text-sm" v-html="announcement.content" />
                </v-card-text>
                <v-card-actions>
                    <Link :href="`/announcements/${announcement.id}/edit`">
                        <v-btn variant="text" size="small">Edit</v-btn>
                    </Link>
                    <v-btn
                        variant="text"
                        size="small"
                        color="error"
                        @click="deleteAnnouncement(announcement.id)"
                    >
                        Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </div>
    </AppLayout>
</template>
