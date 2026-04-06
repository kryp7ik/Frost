<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const page = usePage();
const adminOpen = ref(false);
const userMenuOpen = ref(false);
const mobileMenuOpen = ref(false);

function isActive(path) {
    return page.url.startsWith(path);
}
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Top Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 flex h-14 items-center bg-gray-900 px-4 text-white shadow-lg">
            <!-- Left: Brand + Nav -->
            <div class="flex items-center gap-1">
                <Link href="/" class="mr-4 text-lg font-bold tracking-wide text-white no-underline">Frost POS</Link>

                <template v-if="$page.props.auth?.user">
                    <!-- Mobile hamburger -->
                    <button class="mr-2 text-gray-300 hover:text-white md:hidden" @click="mobileMenuOpen = !mobileMenuOpen">
                        <i class="fa fa-bars text-xl"></i>
                    </button>

                    <!-- Nav links -->
                    <div :class="mobileMenuOpen ? 'flex' : 'hidden'" class="absolute left-0 top-14 w-full flex-col bg-gray-900 md:relative md:top-0 md:flex md:w-auto md:flex-row md:items-center md:gap-1">
                        <Link href="/" :class="$page.url === '/' ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-dashboard mr-1"></i> Dashboard
                        </Link>
                        <Link href="/schedule" :class="isActive('/schedule') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-calendar mr-1"></i> Schedule
                        </Link>
                        <Link href="/customers" :class="isActive('/customers') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-users mr-1"></i> Customers
                        </Link>
                        <Link href="/orders" :class="$page.url === '/orders' ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-server mr-1"></i> Orders
                        </Link>
                        <Link href="/orders/create" :class="isActive('/orders/create') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-plus-square mr-1"></i> New Order
                        </Link>
                        <Link href="/announcements" :class="isActive('/announcements') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'" class="rounded-md px-3 py-2 text-sm md:py-1.5">
                            <i class="fa fa-bullhorn mr-1"></i> Announcements
                        </Link>

                        <!-- Admin Dropdown -->
                        <div v-if="$page.props.auth?.user?.is_manager" class="relative">
                            <button
                                @click="adminOpen = !adminOpen"
                                :class="isActive('/admin') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'"
                                class="flex items-center gap-1 rounded-md px-3 py-2 text-sm md:py-1.5"
                            >
                                <i class="fa fa-cogs mr-1"></i> Admin
                                <i class="fa fa-chevron-down text-xs"></i>
                            </button>
                            <div
                                v-show="adminOpen"
                                @click.away="adminOpen = false"
                                class="absolute left-0 mt-1 w-52 rounded-lg border border-gray-700 bg-gray-800 py-1 shadow-xl md:top-full"
                            >
                                <Link href="/admin/store/products/index" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-tags w-4 text-center"></i> Products
                                </Link>
                                <Link href="/admin/store/products/redline" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-exclamation-triangle w-4 text-center"></i> Redline
                                </Link>
                                <Link href="/admin/store/shipments" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-globe w-4 text-center"></i> Shipments
                                </Link>
                                <Link href="/admin/store/transfers" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-exchange w-4 text-center"></i> Transfers
                                </Link>
                                <Link href="/admin/store/inventory/create" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-database w-4 text-center"></i> Inventory
                                </Link>
                                <Link href="/admin/store/discounts" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-usd w-4 text-center"></i> Discounts
                                </Link>
                                <Link href="/admin/store/recipes" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-tint w-4 text-center"></i> Recipes
                                </Link>
                                <Link href="/admin/store/report/sales" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-area-chart w-4 text-center"></i> Reports
                                </Link>
                                <Link href="/admin/users" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <i class="fa fa-users w-4 text-center"></i> Users
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Right: Store + User -->
            <div class="ml-auto flex items-center gap-4">
                <template v-if="$page.props.auth?.user">
                    <span v-if="$page.props.auth.user.store" class="hidden text-sm text-gray-300 sm:inline">
                        <i class="fa fa-store mr-1"></i>
                        {{ $page.props.auth.user.store_name || `Store ${$page.props.auth.user.store}` }}
                    </span>
                    <span v-else class="hidden text-sm text-amber-400 sm:inline">
                        <i class="fa fa-exclamation-triangle mr-1"></i> Not clocked in
                    </span>

                    <div class="relative">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-gray-200 hover:bg-gray-800 hover:text-white"
                        >
                            <i class="fa fa-user-circle"></i>
                            {{ $page.props.auth.user.name }}
                            <i class="fa fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            v-show="userMenuOpen"
                            @click.away="userMenuOpen = false"
                            class="absolute right-0 mt-1 w-48 rounded-lg border border-gray-700 bg-gray-800 py-1 shadow-xl"
                        >
                            <Link href="/account/edit" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-cog mr-2"></i> Account Settings
                            </Link>
                            <Link href="/account/two-factor" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-shield mr-2"></i> Two-Factor Auth
                            </Link>
                            <div class="my-1 border-t border-gray-700"></div>
                            <Link href="/users/logout" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300">
                                <i class="fa fa-sign-out mr-2"></i> Logout
                            </Link>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <Link href="/users/login" class="rounded-md bg-blue-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-700">Login</Link>
                </template>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-14">
            <div class="mx-auto max-w-7xl p-4 lg:p-6">
                <div
                    v-if="$page.props.flash?.message"
                    class="mb-4 rounded-lg bg-sky-100 px-4 py-3 text-sm text-sky-800"
                >
                    {{ $page.props.flash.message }}
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>
