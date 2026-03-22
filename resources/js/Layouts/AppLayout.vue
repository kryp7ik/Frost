<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <div class="min-h-screen">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <Link class="navbar-brand" href="/">Frost</Link>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <Link class="nav-link" href="/schedule">Schedule</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/customers">Customers</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/orders">Orders</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" href="/announcements">Announcements</Link>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li v-if="$page.props.auth.user" class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                            >
                                {{ $page.props.auth.user.name }}
                                <span
                                    v-if="$page.props.auth.user.two_factor_enabled"
                                    class="badge bg-success ms-1"
                                    title="2FA enabled"
                                >
                                    <i class="fa fa-shield"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <Link class="dropdown-item" href="/account/edit">
                                        Account Settings
                                    </Link>
                                </li>
                                <li>
                                    <Link class="dropdown-item" href="/account/two-factor">
                                        Two-Factor Auth
                                    </Link>
                                </li>
                                <li><hr class="dropdown-divider" /></li>
                                <li>
                                    <Link class="dropdown-item" href="/users/logout">
                                        Logout
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li v-else class="nav-item">
                            <Link class="nav-link" href="/users/login">Login</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid py-4">
            <div
                v-if="$page.props.flash.message"
                class="alert alert-info alert-dismissible"
            >
                {{ $page.props.flash.message }}
            </div>

            <slot />
        </main>
    </div>
</template>
