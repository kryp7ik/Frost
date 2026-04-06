@inject('suspended', 'App\Services\Store\SuspendedOrders')

{{-- Top Navbar --}}
<nav class="fixed top-0 left-0 right-0 z-50 flex h-14 items-center bg-gray-900 px-4 text-white shadow-lg">
    {{-- Left: Brand + Nav Links --}}
    <div class="flex items-center gap-1">
        <a href="/" class="mr-4 text-lg font-bold tracking-wide text-white no-underline">Frost POS</a>

        @auth
            {{-- Mobile hamburger --}}
            <button id="mobile-menu-toggle" class="mr-2 text-gray-300 hover:text-white md:hidden" onclick="document.getElementById('nav-links').classList.toggle('hidden')">
                <i class="fa fa-bars text-xl"></i>
            </button>

            {{-- Nav links (hidden on mobile, shown on md+) --}}
            <div id="nav-links" class="hidden md:flex md:items-center md:gap-1">
                <a href="/" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('/') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-dashboard mr-1"></i> Dashboard
                </a>
                <a href="/schedule" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('schedule*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-calendar mr-1"></i> Schedule
                </a>
                <a href="/customers" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('customers*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-users mr-1"></i> Customers
                </a>
                <a href="/orders" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('orders') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-server mr-1"></i> Orders
                </a>
                <a href="/orders/create" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('orders/create') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-plus-square mr-1"></i> New Order
                </a>
                <a href="/announcements" class="rounded-md px-3 py-1.5 text-sm {{ request()->is('announcements*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fa fa-bullhorn mr-1"></i> Announcements
                </a>

                {{-- Admin Dropdown --}}
                @if(Auth::user()->hasRole('manager'))
                    <div class="relative" id="admin-dropdown">
                        <button onclick="this.parentElement.classList.toggle('open')" class="flex items-center gap-1 rounded-md px-3 py-1.5 text-sm {{ request()->is('admin*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fa fa-cogs mr-1"></i> Admin
                            <i class="fa fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute left-0 mt-1 hidden w-52 rounded-lg border border-gray-700 bg-gray-800 py-1 shadow-xl" id="admin-menu">
                            <a href="/admin/store/products/index" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-tags w-4 text-center"></i> Products
                            </a>
                            <a href="/admin/store/products/redline" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-exclamation-triangle w-4 text-center"></i> Redline
                            </a>
                            <a href="/admin/store/shipments" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-globe w-4 text-center"></i> Shipments
                            </a>
                            <a href="/admin/store/transfers" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-exchange w-4 text-center"></i> Transfers
                            </a>
                            <a href="/admin/store/inventory/create" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-database w-4 text-center"></i> Inventory
                            </a>
                            <a href="/admin/store/discounts" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-usd w-4 text-center"></i> Discounts
                            </a>
                            <a href="/admin/store/recipes" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-tint w-4 text-center"></i> Recipes
                            </a>
                            <a href="/admin/store/ingredients" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-flask w-4 text-center"></i> Ingredients
                            </a>
                            <a href="/admin/store/report/sales" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-area-chart w-4 text-center"></i> Reports
                            </a>
                            <a href="/admin/store/touch" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-hand-pointer-o w-4 text-center"></i> Touch
                            </a>
                            <a href="/admin/users" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                                <i class="fa fa-users w-4 text-center"></i> Users
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endauth
    </div>

    {{-- Right: Store + User --}}
    <div class="ml-auto flex items-center gap-4">
        @auth
            {{-- Active Store --}}
            <span class="hidden text-sm text-gray-300 sm:inline">
                @if(Auth::user()->store)
                    <i class="fa fa-store mr-1"></i>
                    {{ config('store.stores')[Auth::user()->store] }}
                @else
                    <span class="text-amber-400"><i class="fa fa-exclamation-triangle mr-1"></i> Not clocked in</span>
                @endif
            </span>

            {{-- User Dropdown --}}
            <div class="relative" id="user-dropdown">
                <button onclick="this.parentElement.classList.toggle('open')" class="flex items-center gap-2 rounded-md px-3 py-1.5 text-sm text-gray-200 hover:bg-gray-800 hover:text-white">
                    <i class="fa fa-user-circle"></i>
                    {{ Auth::user()->name }}
                    <i class="fa fa-chevron-down text-xs"></i>
                </button>
                <div class="absolute right-0 mt-1 hidden w-48 rounded-lg border border-gray-700 bg-gray-800 py-1 shadow-xl" id="user-menu">
                    @if(Auth::user()->hasRole('admin'))
                        <a href="/admin/users/{{ Auth::user()->id }}/edit" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fa fa-cog mr-2"></i> Account Settings
                        </a>
                    @else
                        <a href="/account/edit" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fa fa-cog mr-2"></i> Account Settings
                        </a>
                    @endif
                    <a href="/account/two-factor" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                        <i class="fa fa-shield mr-2"></i> Two-Factor Auth
                    </a>
                    <div class="my-1 border-t border-gray-700"></div>
                    <a href="/users/logout" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300">
                        <i class="fa fa-sign-out mr-2"></i> Logout
                    </a>
                </div>
            </div>
        @else
            <a href="/users/login" class="rounded-md bg-blue-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-blue-700">Login</a>
        @endauth
    </div>
</nav>

{{-- Suspended Orders Sidebar (only shows when there are suspended orders) --}}
@auth
    @if($suspended->getSuspendedOrders()->count())
        {{-- Collapsed indicator --}}
        <aside id="sidebar-collapsed" class="fixed top-14 left-0 z-40 flex h-[calc(100vh-3.5rem)] w-10 flex-col items-center border-r border-gray-800 bg-gray-900 py-3">
            <button onclick="document.getElementById('sidebar-collapsed').classList.add('hidden'); document.getElementById('sidebar-expanded').classList.remove('hidden');" class="relative text-amber-500 hover:text-amber-400" title="Suspended Orders">
                <i class="fa fa-shopping-bag text-lg"></i>
                <span class="absolute -right-1.5 -top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white">
                    {{ $suspended->getSuspendedOrders()->count() }}
                </span>
            </button>
            <button onclick="document.getElementById('sidebar-collapsed').classList.add('hidden'); document.getElementById('sidebar-expanded').classList.remove('hidden');" class="mt-3 text-gray-500 hover:text-gray-300" title="Expand sidebar">
                <i class="fa fa-chevron-right text-xs"></i>
            </button>
        </aside>

        {{-- Expanded sidebar --}}
        <aside id="sidebar-expanded" class="fixed top-14 left-0 z-40 hidden h-[calc(100vh-3.5rem)] w-56 overflow-y-auto border-r border-gray-800 bg-gray-900">
            <div class="flex items-center justify-between px-4 pt-4 pb-2">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-500">
                    <i class="fa fa-shopping-bag mr-1"></i> Suspended Orders
                </p>
                <button onclick="document.getElementById('sidebar-expanded').classList.add('hidden'); document.getElementById('sidebar-collapsed').classList.remove('hidden');" class="text-gray-500 hover:text-gray-300" title="Collapse sidebar">
                    <i class="fa fa-chevron-left text-xs"></i>
                </button>
            </div>
            <ul class="space-y-1 px-3 pb-4">
                @foreach($suspended->getSuspendedOrders() as $order)
                    <li>
                        <a href="/orders/{{ $order->id }}/show" class="flex items-center gap-3 rounded-md px-3 py-2 text-sm text-amber-400 hover:bg-gray-800 hover:text-amber-300">
                            <i class="fa fa-shopping-bag w-5 text-center"></i> Suspended ${{ $order->total }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
    @endif
@endauth

{{-- Dropdown toggle script --}}
<script>
    document.addEventListener('click', function(e) {
        // Close all dropdown menus when clicking outside
        var dropdowns = ['user-dropdown', 'admin-dropdown'];
        dropdowns.forEach(function(id) {
            var el = document.getElementById(id);
            if (el && !el.contains(e.target)) {
                el.classList.remove('open');
                var menu = el.querySelector('[id$="-menu"]');
                if (menu) menu.classList.add('hidden');
            }
        });

        // Toggle menu on click
        var userDropdown = document.getElementById('user-dropdown');
        var userMenu = document.getElementById('user-menu');
        if (userDropdown && userMenu && userDropdown.contains(e.target)) {
            userMenu.classList.toggle('hidden');
        }

        var adminDropdown = document.getElementById('admin-dropdown');
        var adminMenu = document.getElementById('admin-menu');
        if (adminDropdown && adminMenu && adminDropdown.contains(e.target)) {
            adminMenu.classList.toggle('hidden');
        }
    });
</script>
