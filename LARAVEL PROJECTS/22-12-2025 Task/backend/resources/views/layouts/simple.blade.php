<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @auth
    <meta name="api-token" content="{{ Auth::user()->createToken('api')->plainTextToken }}">
    @endauth
    
    <title>@yield('title', 'SmartTask - Task Management')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Main Navigation -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 flex items-center">
                        <i class="fas fa-tasks mr-2"></i>
                        SmartTask
                    </a>
                    
                    <!-- Navigation Links (Desktop) -->
                    @auth
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>
                        <a href="{{ route('tasks.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tasks.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-list-check mr-1"></i> Tasks
                        </a>
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <a href="{{ route('teams.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('teams.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-users mr-1"></i> Teams
                        </a>
                        @endif
                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-user-cog mr-1"></i> Users
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
                
                <!-- Right Navigation -->
                <div class="flex items-center">
                    @auth
                    <!-- User Dropdown -->
                    <div class="relative ml-3">
                        <button id="user-menu-button" 
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="mr-2 text-gray-700">{{ Auth::user()->name }}</span>
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="user-dropdown" 
                             class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-10">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="px-2 py-1 bg-gray-100 rounded">{{ ucfirst(Auth::user()->role) }}</span>
                                </p>
                            </div>
                            @if(Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i> Profile Settings
                            </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Guest Links -->
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    </div>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" 
                            class="text-gray-500 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    @auth
                    <a href="{{ route('dashboard') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('tasks.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tasks.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-list-check mr-2"></i> Tasks
                    </a>
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    <a href="{{ route('teams.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('teams.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users mr-2"></i> Teams
                    </a>
                    @endif
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('users.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user-cog mr-2"></i> Users
                    </a>
                    @endif
                    @if(Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-cog mr-2"></i> Profile
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Validation Errors -->
    @if($errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Please fix the following errors:</strong>
            <ul class="mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-tasks mr-1"></i> SmartTask &copy; {{ date('Y') }} - Task Management System
                </p>
                <div class="mt-2 md:mt-0">
                    <span class="text-gray-400 text-sm">
                        Laravel {{ app()->version() }}
                    </span>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // User dropdown toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // User dropdown
            const userButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            if (userButton && userDropdown && !userButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
            
            // Mobile menu
            const mobileButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileButton && mobileMenu && !mobileButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // Close flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('[role="alert"]').forEach(alert => {
                alert.remove();
            });
        }, 5000);
        
        // Get authentication token for API calls
        function getAuthToken() {
            const tokenMeta = document.querySelector('meta[name="api-token"]');
            return tokenMeta ? tokenMeta.getAttribute('content') : '';
        }
    </script>
    
    <!-- Additional Scripts from Views -->
    @yield('scripts')
</body>
</html>