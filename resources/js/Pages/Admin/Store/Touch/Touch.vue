<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, onUnmounted, ref, computed } from 'vue';

const liquids = ref([]);
const mixed = ref([]);
const selected = ref([]);
const loading = ref(false);
let pollHandle = null;

const mentholLabels = { 0: 'None', 1: 'Light', 2: 'Medium', 3: 'Heavy', 4: 'Super' };
const vgLabels = { 40: '40%', 60: '60%', 100: 'MAX' };

const pendingHeaders = [
    { title: 'Order', key: 'shop_order_id', width: '70px' },
    { title: 'Recipe', key: 'recipe' },
    { title: 'Size', key: 'size', width: '70px' },
    { title: 'Nic', key: 'nicotine', width: '60px' },
    { title: 'VG', key: 'vg_label', width: '70px' },
    { title: 'Menthol', key: 'menthol_label', width: '90px' },
    { title: 'Salt', key: 'salt', width: '60px' },
    { title: 'Extra', key: 'extra', width: '60px' },
    { title: 'Ingredients', key: 'ingredient_list', sortable: false },
];

const pendingItems = computed(() =>
    liquids.value.map((l) => ({
        ...l,
        vg_label: vgLabels[l.vg] ?? `${l.vg}%`,
        menthol_label: mentholLabels[l.menthol] ?? l.menthol,
        ingredient_list: (l.ingredients || []).map((i) => `${i.name} (${i.amount}ml)`).join(', '),
    })),
);

const mixedHeaders = [
    { title: 'Recipe', key: 'recipe' },
    { title: 'Size', key: 'size', width: '70px' },
    { title: 'Nic', key: 'nicotine', width: '60px' },
    { title: '', key: 'actions', sortable: false, width: '60px' },
];

async function load() {
    loading.value = true;
    try {
        const [liquidsRes, mixedRes] = await Promise.all([
            axios.get('/admin/store/touch/get-liquids'),
            axios.get('/admin/store/touch/get-mixed'),
        ]);
        liquids.value = liquidsRes.data || [];
        mixed.value = mixedRes.data || [];
        // Remove selected items that no longer exist in pending
        const ids = new Set(liquids.value.map((l) => l.id));
        selected.value = selected.value.filter((s) => ids.has(s.id));
    } finally {
        loading.value = false;
    }
}

async function completeSelected() {
    if (!selected.value.length) return;
    await axios.post(
        '/admin/store/touch/complete',
        selected.value.map((s) => s.id),
    );
    selected.value = [];
    await load();
}

async function unmix(id) {
    await axios.get(`/admin/store/touch/unmix/${id}`);
    await load();
}

onMounted(() => {
    load();
    pollHandle = setInterval(load, 15_000);
});

onUnmounted(() => {
    if (pollHandle) clearInterval(pollHandle);
});
</script>

<template>
    <Head title="Touch" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Liquid Mixer</h1>
            <div class="flex items-center gap-3">
                <v-progress-circular v-if="loading" size="20" indeterminate color="primary" />
                <v-btn
                    color="primary"
                    :disabled="!selected.length"
                    data-testid="touch-complete"
                    @click="completeSelected"
                >
                    <v-icon start icon="mdi-check-all" />
                    Complete{{ selected.length ? ` (${selected.length})` : '' }}
                </v-btn>
            </div>
        </div>

        <!-- Pending Liquids Table (full width) -->
        <v-card class="mb-4" data-testid="touch-pending-card">
            <v-card-title>Pending</v-card-title>
            <v-data-table
                v-model="selected"
                :headers="pendingHeaders"
                :items="pendingItems"
                item-value="id"
                show-select
                return-object
                :items-per-page="-1"
                density="compact"
                class="text-sm"
                no-data-text="No pending liquids."
            >
                <template #item.shop_order_id="{ item }">
                    <span class="font-medium">#{{ item.shop_order_id }}</span>
                </template>
                <template #item.size="{ item }">
                    {{ item.size }}ml
                </template>
                <template #item.nicotine="{ item }">
                    {{ item.nicotine }}mg
                </template>
                <template #item.salt="{ item }">
                    <v-icon v-if="item.salt" icon="mdi-check" size="small" color="success" />
                </template>
                <template #item.extra="{ item }">
                    <v-icon v-if="item.extra" icon="mdi-check" size="small" color="success" />
                </template>
                <template #item.ingredient_list="{ item }">
                    <span class="text-xs text-gray-600">{{ item.ingredient_list }}</span>
                </template>
            </v-data-table>
        </v-card>

        <!-- Recently Completed -->
        <v-card data-testid="touch-mixed-card">
            <v-card-title>Recently Completed</v-card-title>
            <v-data-table
                :headers="mixedHeaders"
                :items="mixed"
                :items-per-page="5"
                density="compact"
                class="text-sm"
                no-data-text="No recent liquids."
                hide-default-footer
            >
                <template #item.size="{ item }">
                    {{ item.size }}ml
                </template>
                <template #item.nicotine="{ item }">
                    {{ item.nicotine }}mg
                </template>
                <template #item.actions="{ item }">
                    <v-btn
                        size="x-small"
                        variant="text"
                        color="warning"
                        icon="mdi-undo"
                        @click="unmix(item.id)"
                    />
                </template>
            </v-data-table>
        </v-card>
    </AppLayout>
</template>
