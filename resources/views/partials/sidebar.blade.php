<aside
    class="hidden md:flex flex-col bg-white dark:bg-gray-700 w-[240px] h-full shrink-0 transition-colors duration-300">
    <div class="h-16 flex items-center px-4">
        <h1 class="font-bold tracking-wide text-green-600 dark:text-gray-200">Report Management</h1>
    </div>

    <div class="flex flex-col py-8 space-y-3">
        <x-sidebar-item route="{{ route('dashboard') }}" pattern="dashboard" icon="fa-layer-group" label="Dashboard" />

        <x-sidebar-item route="{{ route('product.index') }}" pattern="product.*" icon="fa-server" label="Product" />

        @if ($user->role === 'admin')
            <x-sidebar-item route="{{ route('user.index') }}" pattern="user.*" icon="fa-user" label="User" />
        @endif

        <form id="form-logout" action="{{ route('logout') }}" method="POST">
            @csrf

            <x-sidebar-item variant="logout" />
        </form>
    </div>
</aside>
