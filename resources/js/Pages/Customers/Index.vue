<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    customers: {
        type: Array,
        default: () => [],
    },
});

const showCreateForm = ref(false);

const form = useForm({
    name: '',
    phone: '',
    email: '',
});

const submit = () => {
    form.post('/customers/create', {
        onSuccess: () => {
            form.reset();
            showCreateForm.value = false;
        },
    });
};
</script>

<template>
    <Head title="Customers" />

    <AppLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Customers</h1>
            <button
                class="btn btn-primary"
                @click="showCreateForm = !showCreateForm"
            >
                {{ showCreateForm ? 'Cancel' : 'New Customer' }}
            </button>
        </div>

        <div v-if="showCreateForm" class="card mb-4">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.name }"
                                placeholder="Name"
                            />
                        </div>
                        <div class="col-md-4">
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.phone }"
                                placeholder="Phone"
                                required
                            />
                        </div>
                        <div class="col-md-3">
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': form.errors.email }"
                                placeholder="Email"
                            />
                        </div>
                        <div class="col-md-1">
                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                :disabled="form.processing"
                            >
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Points</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers" :key="customer.id">
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.points }}</td>
                        <td>
                            <Link
                                :href="`/customers/${customer.id}/show`"
                                class="btn btn-sm btn-outline-primary"
                            >
                                View
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="!customers.length">
                        <td colspan="5" class="text-center text-muted">
                            No customers found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
