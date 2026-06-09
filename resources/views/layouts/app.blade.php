<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WorkFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dark Mode Global Settings -->
        <script>
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }

            function toggleTheme() {
                const html = document.documentElement;
                const isDark = html.classList.toggle('dark-theme');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                _syncThemeIcons();
            }

            function setTheme(theme) {
                const html = document.documentElement;
                if (theme === 'dark') {
                    html.classList.add('dark-theme');
                } else {
                    html.classList.remove('dark-theme');
                }
                localStorage.setItem('theme', theme);
                _syncThemeIcons();
            }

            function _syncThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark-theme');
                document.querySelectorAll('.theme-icon-sun').forEach(el => el.classList.toggle('hidden', !isDark));
                document.querySelectorAll('.theme-icon-moon').forEach(el => el.classList.toggle('hidden', isDark));
                document.querySelectorAll('[data-theme-btn]').forEach(btn => {
                    btn.classList.toggle('border-brand-green', btn.dataset.themeBtn === (isDark ? 'dark' : 'light'));
                    btn.classList.toggle('text-brand-green', btn.dataset.themeBtn === (isDark ? 'dark' : 'light'));
                });
            }

            document.addEventListener('DOMContentLoaded', _syncThemeIcons);
        </script>
        <style>
            /* Sistema de Dark Mode Global simplificado */
            html.dark-theme body, html.dark-theme .bg-\[\#F5F7FA\] {
                background-color: #0f172a !important; /* slate-900 */
                color: #e2e8f0 !important;
            }
            html.dark-theme .bg-white {
                background-color: #1e293b !important; /* slate-800 */
                border-color: #334155 !important;
            }
            html.dark-theme .text-\[\#1E3A5F\], html.dark-theme .text-gray-700 {
                color: #f8fafc !important; /* slate-50 */
            }
            html.dark-theme .text-gray-500, html.dark-theme .text-gray-600 {
                color: #94a3b8 !important; /* slate-400 */
            }
            html.dark-theme .border-gray-100, html.dark-theme .border-gray-200 {
                border-color: #334155 !important;
            }
            html.dark-theme input, html.dark-theme textarea, html.dark-theme select {
                background-color: #0f172a !important;
                color: #f8fafc !important;
                border-color: #334155 !important;
            }
            /* Mantener el Sidebar y botones verdes intactos */
            html.dark-theme .bg-\[\#1E3A5F\] {
                background-color: #1E3A5F !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#F5F7FA] transition-colors duration-300">
        @hasSection('content')
            <!-- Nuestras Vistas Custom Desktop -->
            @yield('content')
        @else
            <!-- Compatibilidad con componentes de Laravel Breeze originales -->
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot ?? '' }}
                </main>
            </div>
        @endif
    </body>
</html>
