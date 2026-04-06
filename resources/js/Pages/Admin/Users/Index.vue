<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: { type: Array, default: () => [] },
    includeTrashed: { type: Boolean, default: false },
});

const showTrashed = ref(props.includeTrashed);

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Email', key: 'email' },
    { title: 'Store', key: 'store' },
    { title: 'Roles', key: 'roles' },
    { title: 'Status', key: 'status' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

function toggleTrashed() {
    router.get('/admin/users', { trashed: showTrashed.value ? 'true' : undefined }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function deleteUser(id) {
    if (!confirm('Soft-delete this user?')) {
        return;
    }
    router.get(`/admin/users/${id}/delete`);
}

function restoreUser(id) {
    router.get(`/admin/users/${id}/restore`);
}
</script>

<template>
    <Head title="Users" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Users</h1>
            <div class="flex items-center gap-3">
                <v-switch
                    v-model="showTrashed"
                    label="Include trashed"
                    hide-details
                    density="compact"
                    color="primary"
                    data-testid="users-trashed-toggle"
                    @update:model-value="toggleTrashed"
                />
                <Link href="/admin/users/create">
                    <v-btn color="primary" data-testid="users-create-button">
                        <v-icon start icon="mdi-plus" /> New User
                    </v-btn>
                </Link>
            </div>
        </div>

        <v-card data-testid="users-table-card">
            <v-data-table
                :headers="headers"
                :items="users"
                item-value="id"
                density="comfortable"
                :items-per-page="25"
            >
                <template #item.roles="{ item }">
                    <v-chip
                        v-for="role in item.roles"
                        :key="role"
                        size="small"
                        class="mr-1"
                    >
                        {{ role }}
                    </v-chip>
                </template>
                <template #item.status="{ item }">
                    <v-chip
                        :color="item.deleted_at ? 'error' : 'success'"
                        size="small"
                        variant="tonal"
                    >
                        {{ item.deleted_at ? 'Trashed' : 'Active' }}
                    </v-chip>
                </template>
                <template #item.actions="{ item }">
                    <Link :href="`/admin/users/${item.id}/edit`">
                        <v-btn
                            size="small"
                            variant="text"
                            icon="mdi-pencil"
                            :data-testid="`user-edit-${item.id}`"
                        />
                    </Link>
                    <v-btn
                        v-if="!item.deleted_at"
                        size="small"
                        variant="text"
                        color="error"
                        icon="mdi-delete"
                        :data-testid="`user-delete-${item.id}`"
                        @click="deleteUser(item.id)"
                    />
                    <v-btn
                        v-else
                        size="small"
                        variant="text"
                        color="success"
                        icon="mdi-restore"
                        :data-testid="`user-restore-${item.id}`"
                        @click="restoreUser(item.id)"
                    />
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
