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
                        <li v-if="$page.props.auth.user" class="nav-item">
                            <span class="navbar-text me-3">
                                {{ $page.props.auth.user.name }}
                            </span>
                        </li>
                        <li v-if="$page.props.auth.user" class="nav-item">
                            <Link class="nav-link" href="/users/logout">Logout</Link>
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
