<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, onUnmounted, ref } from 'vue';

const liquids = ref([]);
const mixed = ref([]);
const selected = ref(new Set());
const loading = ref(false);
let pollHandle = null;

async function load() {
    loading.value = true;
    try {
        const [liquidsRes, mixedRes] = await Promise.all([
            axios.get('/admin/store/touch/get-liquids'),
            axios.get('/admin/store/touch/get-mixed'),
        ]);
        liquids.value = liquidsRes.data || [];
        mixed.value = mixedRes.data || [];
    } finally {
        loading.value = false;
    }
}

function toggle(id) {
    if (selected.value.has(id)) {
        selected.value.delete(id);
    } else {
        selected.value.add(id);
    }
    selected.value = new Set(selected.value); // trigger reactivity
}

async function completeSelected() {
    if (!selected.value.size) {
        return;
    }
    await axios.post('/admin/store/touch/complete', Array.from(selected.value));
    selected.value = new Set();
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
    if (pollHandle) {
        clearInterval(pollHandle);
    }
});
</script>

<template>
    <Head title="Touch" />
    <AppLayout>
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Liquid Mixer</h1>
            <v-btn
                color="primary"
                :disabled="!selected.size"
                data-testid="touch-complete"
                @click="completeSelected"
            >
                <v-icon start icon="mdi-check-all" /> Complete {{ selected.size || '' }}
            </v-btn>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <v-card data-testid="touch-pending-card">
                <v-card-title>
                    Pending
                    <v-spacer />
                    <v-progress-circular v-if="loading" size="20" indeterminate color="primary" />
                </v-card-title>
                <v-card-text>
                    <p v-if="!liquids.length" class="text-sm text-gray-500">No pending liquids.</p>
                    <v-list density="compact">
                        <v-list-item
                            v-for="liquid in liquids"
                            :key="liquid.id"
                            :title="liquid.label || liquid.name || `Liquid #${liquid.id}`"
                            :subtitle="liquid.ingredients || liquid.description || ''"
                            :active="selected.has(liquid.id)"
                            @click="toggle(liquid.id)"
                        >
                            <template #prepend>
                                <v-checkbox-btn :model-value="selected.has(liquid.id)" />
                            </template>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>

            <v-card data-testid="touch-mixed-card">
                <v-card-title>Recently Completed</v-card-title>
                <v-card-text>
                    <p v-if="!mixed.length" class="text-sm text-gray-500">No recent liquids.</p>
                    <v-list density="compact">
                        <v-list-item
                            v-for="liquid in mixed"
                            :key="liquid.id"
                            :title="liquid.label || liquid.name || `Liquid #${liquid.id}`"
                        >
                            <template #append>
                                <v-btn
                                    size="small"
                                    variant="text"
                                    color="warning"
                                    icon="mdi-undo"
                                    @click="unmix(liquid.id)"
                                />
                            </template>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </div>
    </AppLayout>
</template>
