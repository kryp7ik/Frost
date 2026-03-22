<script setup>
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCodeSvg = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);
const confirmationCode = ref('');
const confirmationError = ref(null);

const twoFactorEnabled = computed(
    () => page.props.auth.user?.two_factor_enabled ?? false
);

const enableTwoFactor = () => {
    enabling.value = true;

    router.post('/user/two-factor-authentication', {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]).then(() => {
            confirming.value = true;
        }),
        onFinish: () => {
            enabling.value = false;
        },
    });
};

const showQrCode = () => {
    return axios.get('/user/two-factor-qr-code').then((response) => {
        qrCodeSvg.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get('/user/two-factor-secret-key').then((response) => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get('/user/two-factor-recovery-codes').then((response) => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactor = () => {
    confirmationError.value = null;

    router.post('/user/confirmed-two-factor-authentication', {
        code: confirmationCode.value,
    }, {
        preserveScroll: true,
        errorBag: 'confirmTwoFactorAuthentication',
        onSuccess: () => {
            confirming.value = false;
            qrCodeSvg.value = null;
            setupKey.value = null;
            confirmationCode.value = '';
        },
        onError: (errors) => {
            confirmationError.value = errors.code ?? 'Invalid verification code.';
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios.post('/user/two-factor-recovery-codes').then(() => {
        showRecoveryCodes();
    });
};

const disableTwoFactor = () => {
    disabling.value = true;

    router.delete('/user/two-factor-authentication', {
        preserveScroll: true,
        onSuccess: () => {
            confirming.value = false;
            qrCodeSvg.value = null;
            setupKey.value = null;
            recoveryCodes.value = [];
        },
        onFinish: () => {
            disabling.value = false;
        },
    });
};
</script>

<template>
    <Head title="Two-Factor Authentication" />

    <AppLayout>
        <div class="row">
            <div class="col-12">
                <h1>Two-Factor Authentication</h1>
                <p class="text-muted">
                    Add additional security to your account using two-factor authentication.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span v-if="twoFactorEnabled && !confirming" class="badge bg-success me-2">Enabled</span>
                        <span v-else-if="confirming" class="badge bg-warning me-2">Pending Confirmation</span>
                        <span v-else class="badge bg-secondary me-2">Disabled</span>
                        Authenticator App
                    </div>

                    <div class="card-body">
                        <p>
                            When two-factor authentication is enabled, you will be prompted
                            for a secure, random token during authentication. You may retrieve
                            this token from your phone's Google Authenticator, Authy, or
                            1Password application.
                        </p>

                        <!-- QR Code + Setup Key (shown during enable/confirm flow) -->
                        <div v-if="qrCodeSvg" class="mt-4">
                            <p class="fw-bold">
                                Scan the following QR code using your authenticator app:
                            </p>
                            <div class="p-3 bg-white d-inline-block border rounded" v-html="qrCodeSvg"></div>

                            <p v-if="setupKey" class="mt-3 mb-0">
                                <strong>Setup Key:</strong>
                                <code class="user-select-all">{{ setupKey }}</code>
                            </p>
                        </div>

                        <!-- Confirmation input -->
                        <div v-if="confirming" class="mt-4">
                            <label for="confirmation-code" class="form-label fw-bold">
                                Enter the 6-digit code from your app to confirm:
                            </label>
                            <div class="input-group" style="max-width: 320px;">
                                <input
                                    id="confirmation-code"
                                    v-model="confirmationCode"
                                    type="text"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    class="form-control"
                                    :class="{ 'is-invalid': confirmationError }"
                                    placeholder="123456"
                                    @keyup.enter="confirmTwoFactor"
                                />
                                <button
                                    class="btn btn-primary"
                                    type="button"
                                    @click="confirmTwoFactor"
                                >
                                    Confirm
                                </button>
                            </div>
                            <div v-if="confirmationError" class="text-danger small mt-1">
                                {{ confirmationError }}
                            </div>
                        </div>

                        <!-- Recovery codes -->
                        <div v-if="recoveryCodes.length > 0" class="mt-4">
                            <p class="fw-bold mb-1">Recovery Codes</p>
                            <p class="text-muted small">
                                Store these recovery codes in a secure password manager.
                                They can be used to recover access to your account if your
                                two-factor authentication device is lost.
                            </p>
                            <div class="bg-light p-3 rounded font-monospace small">
                                <div v-for="code in recoveryCodes" :key="code">{{ code }}</div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="mt-4">
                            <button
                                v-if="!twoFactorEnabled && !confirming"
                                class="btn btn-primary"
                                :disabled="enabling"
                                @click="enableTwoFactor"
                            >
                                {{ enabling ? 'Enabling...' : 'Enable Two-Factor' }}
                            </button>

                            <template v-else>
                                <button
                                    v-if="twoFactorEnabled && recoveryCodes.length === 0"
                                    class="btn btn-outline-secondary me-2"
                                    @click="showRecoveryCodes"
                                >
                                    Show Recovery Codes
                                </button>
                                <button
                                    v-if="twoFactorEnabled && recoveryCodes.length > 0"
                                    class="btn btn-outline-secondary me-2"
                                    @click="regenerateRecoveryCodes"
                                >
                                    Regenerate Recovery Codes
                                </button>
                                <button
                                    class="btn btn-danger"
                                    :disabled="disabling"
                                    @click="disableTwoFactor"
                                >
                                    {{ disabling ? 'Disabling...' : 'Disable Two-Factor' }}
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
