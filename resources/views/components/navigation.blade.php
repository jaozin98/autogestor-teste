@props(['currentRoute' => null])

<nav class="nav-header">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="nav-brand">
            <x-application-logo />
        </a>

        <div class="nav-menu">
            @unless (Auth::user()->hasAnyRole(['admin', 'super_admin']))
                <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')" class="nav-link products">
                    <i class="fas fa-box"></i>
                    Produtos
                </x-nav-link>

                <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.*')" class="nav-link categories">
                    <i class="fas fa-tags"></i>
                    Categorias
                </x-nav-link>

                <x-nav-link href="{{ route('brands.index') }}" :active="request()->routeIs('brands.*')" class="nav-link brands">
                    <i class="fas fa-copyright"></i>
                    Marcas
                </x-nav-link>
            @endunless

            @role('admin')
                <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" class="nav-link users">
                    <i class="fas fa-users"></i>
                    Usuários
                </x-nav-link>
                <x-nav-link href="{{ route('roles.index') }}" :active="request()->routeIs('roles.*')" class="nav-link roles">
                    <i class="fas fa-user-tag"></i>
                    Roles
                </x-nav-link>
                <x-nav-link href="{{ route('permissions.index') }}" :active="request()->routeIs('permissions.*')" class="nav-link permissions">
                    <i class="fas fa-key"></i>
                    Permissões
                </x-nav-link>
            @endrole

            <div class="nav-dropdown">
                <button class="nav-link user-menu" onclick="toggleUserMenu()">
                    <i class="fas fa-user"></i>
                    {{ Auth::user()->name }}
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu" id="userDropdown">
                    <x-dropdown-link href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-edit"></i>
                        Perfil
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('show');
    }

    // Fechar dropdown quando clicar fora
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const userMenu = document.querySelector('.user-menu');

        if (!userMenu.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>
