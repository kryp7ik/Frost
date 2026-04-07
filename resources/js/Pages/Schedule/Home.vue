<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import timeGridPlugin from '@fullcalendar/timegrid';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import axios from 'axios';

const props = defineProps({
    users: { type: Array, default: () => [] },
    stores: { type: Array, default: () => [] },
});

const calendarRef = ref(null);

// Create shift dialog state
const createDialog = ref(false);
const selectedEmployee = ref(null);
const pendingSelection = ref(null);

// Delete shift dialog state
const deleteDialog = ref(false);
const pendingDelete = ref(null);

const userItems = computed(() =>
    props.users.map((u) => ({
        title: `${u.name} — ${props.stores.find((s) => s.id === u.store)?.name || 'No store'}`,
        value: u.id,
        store: u.store,
    })),
);

const calendarOptions = {
    plugins: [timeGridPlugin, dayGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,timeGridDay,dayGridMonth',
    },
    slotMinTime: '06:00:00',
    slotMaxTime: '23:00:00',
    allDaySlot: false,
    editable: true,
    selectable: true,
    selectMirror: true,
    eventResizableFromStart: true,
    height: 'auto',
    nowIndicator: true,
    slotDuration: '00:30:00',

    // Load events from the shift API
    events: async (fetchInfo, successCallback, failureCallback) => {
        try {
            const { data } = await axios.get('/shift', {
                params: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                },
            });
            successCallback(data);
        } catch (err) {
            failureCallback(err);
        }
    },

    // Drag-and-drop: move a shift
    eventDrop: async (info) => {
        try {
            await axios.put(`/shift/${info.event.id}`, {
                start: info.event.startStr,
                end: info.event.endStr,
            });
        } catch {
            info.revert();
        }
    },

    // Resize: change shift duration
    eventResize: async (info) => {
        try {
            await axios.put(`/shift/${info.event.id}`, {
                start: info.event.startStr,
                end: info.event.endStr,
            });
        } catch {
            info.revert();
        }
    },

    // Click+drag on empty space: create a new shift
    select: (info) => {
        pendingSelection.value = info;
        selectedEmployee.value = null;
        createDialog.value = true;
    },

    // Click on event: delete shift
    eventClick: (info) => {
        pendingDelete.value = info.event;
        deleteDialog.value = true;
    },
};

async function confirmCreate() {
    if (!selectedEmployee.value || !pendingSelection.value) return;
    const user = props.users.find((u) => u.id === selectedEmployee.value);
    try {
        await axios.post('/shift', {
            user: selectedEmployee.value,
            storeid: user?.store || 1,
            start: pendingSelection.value.startStr,
        });
        calendarRef.value?.getApi()?.refetchEvents();
    } finally {
        createDialog.value = false;
        pendingSelection.value = null;
    }
}

async function confirmDelete() {
    if (!pendingDelete.value) return;
    try {
        await axios.delete(`/shift/${pendingDelete.value.id}`);
        pendingDelete.value.remove();
    } finally {
        deleteDialog.value = false;
        pendingDelete.value = null;
    }
}
</script>

<template>
    <Head title="Schedule" />
    <AppLayout>
        <h1 class="mb-4 text-2xl font-semibold text-gray-800">Schedule</h1>

        <!-- Calendar -->
        <v-card data-testid="schedule-card" class="mb-4">
            <v-card-text data-testid="schedule-calendar">
                <FullCalendar ref="calendarRef" :options="calendarOptions" />
            </v-card-text>
        </v-card>

        <!-- Employee Legend -->
        <v-card data-testid="schedule-legend">
            <v-card-title class="text-base">Employees</v-card-title>
            <v-card-text>
                <div class="flex flex-wrap gap-4">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="flex items-center gap-2 text-sm"
                    >
                        <span
                            class="inline-block h-3 w-3 rounded-full"
                            :style="{ backgroundColor: user.color || '#999' }"
                        />
                        <span>{{ user.name }}</span>
                        <span class="text-xs text-gray-400">
                            {{ stores.find((s) => s.id === user.store)?.name }}
                        </span>
                    </div>
                </div>
            </v-card-text>
        </v-card>

        <!-- Create Shift Dialog -->
        <v-dialog v-model="createDialog" max-width="400" data-testid="schedule-create-dialog">
            <v-card>
                <v-card-title>New Shift</v-card-title>
                <v-card-text>
                    <p class="mb-3 text-sm text-gray-600">
                        {{ pendingSelection?.start?.toLocaleString() }}
                    </p>
                    <v-select
                        v-model="selectedEmployee"
                        :items="userItems"
                        label="Employee"
                        data-testid="schedule-create-employee"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn variant="text" @click="createDialog = false">Cancel</v-btn>
                    <v-btn
                        color="primary"
                        :disabled="!selectedEmployee"
                        data-testid="schedule-create-submit"
                        @click="confirmCreate"
                    >
                        Create
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Delete Shift Dialog -->
        <v-dialog v-model="deleteDialog" max-width="400">
            <v-card>
                <v-card-title>Delete Shift</v-card-title>
                <v-card-text>
                    <p class="text-sm">
                        Remove <strong>{{ pendingDelete?.title }}</strong>'s shift on
                        {{ pendingDelete?.start?.toLocaleString() }}?
                    </p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn variant="text" @click="deleteDialog = false">Cancel</v-btn>
                    <v-btn color="error" @click="confirmDelete">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>

<style>
/* FullCalendar base styles are auto-imported by the plugins.
   Override a few things to blend with the app's design. */
.fc .fc-toolbar-title {
    font-size: 1.15rem;
    font-weight: 600;
}
.fc .fc-button {
    font-size: 0.8rem;
    padding: 0.3rem 0.6rem;
}
</style>
