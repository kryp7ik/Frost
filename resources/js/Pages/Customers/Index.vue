<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    customers: { type: Array, default: () => [] },
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Phone', key: 'phone' },
    { title: 'Email', key: 'email' },
    { title: 'Points', key: 'points' },
    { title: '', key: 'actions', sortable: false, align: 'end' },
];

const dialog = ref(false);

const form = useForm({
    name: '',
    phone: '',
    email: '',
});

function submit() {
    form.post('/customers/create', {
        onSuccess: () => {
            form.reset();
            dialog.value = false;
        },
    });
}
</script>

<template>
    <Head title="Customers" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Customers</h1>
            <v-btn
                color="primary"
                data-testid="customers-create-button"
                @click="dialog = true"
            >
                <v-icon start icon="mdi-plus" /> New Customer
            </v-btn>
        </div>

        <v-card data-testid="customers-table-card">
            <v-data-table
                :headers="headers"
                :items="customers"
                item-value="id"
                density="comfortable"
                :items-per-page="50"
            >
                <template #item.actions="{ item }">
                    <Link :href="`/customers/${item.id}/show`">
                        <v-btn size="small" variant="text" icon="mdi-eye" :data-testid="`customer-view-${item.id}`" />
                    </Link>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" max-width="520" data-testid="customer-dialog">
            <v-card>
                <v-card-title>New Customer</v-card-title>
                <v-card-text>
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            v-model="form.name"
                            label="Name"
                            :error-messages="form.errors.name"
                            data-testid="customer-name"
                        />
                        <v-text-field
                            v-model="form.phone"
                            label="Phone"
                            :error-messages="form.errors.phone"
                            required
                            data-testid="customer-phone"
                        />
                        <v-text-field
                            v-model="form.email"
                            label="Email"
                            type="email"
                            :error-messages="form.errors.email"
                            data-testid="customer-email"
                        />
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn variant="text" @click="dialog = false">Cancel</v-btn>
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        data-testid="customer-submit"
                        @click="submit"
                    >
                        Create
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
