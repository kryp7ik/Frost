<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    customer: {
        type: Object,
        required: true,
    },
    orders: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head :title="customer.name" />

    <AppLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ customer.name }}</h1>
            <Link href="/customers" class="btn btn-secondary">Back</Link>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <dl>
                            <dt>Phone</dt>
                            <dd>{{ customer.phone }}</dd>
                            <dt>Email</dt>
                            <dd>{{ customer.email || '—' }}</dd>
                            <dt>Points</dt>
                            <dd>{{ customer.points }}</dd>
                            <dt>Preferred</dt>
                            <dd>{{ customer.preferred ? 'Yes' : 'No' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Order History</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in orders" :key="order.id">
                                    <td>{{ order.id }}</td>
                                    <td>{{ order.created_at }}</td>
                                    <td>${{ Number(order.total).toFixed(2) }}</td>
                                    <td>
                                        <span
                                            class="badge"
                                            :class="order.complete ? 'bg-success' : 'bg-warning'"
                                        >
                                            {{ order.complete ? 'Complete' : 'Open' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!orders.length">
                                    <td colspan="4" class="text-center text-muted">
                                        No orders yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
