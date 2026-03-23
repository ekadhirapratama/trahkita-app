<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TrahKita') }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Public_Sans:wght@400;500;600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <style>
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; display: inline-block; line-height: 1; text-transform: none; letter-spacing: normal; word-wrap: normal; white-space: nowrap; direction: ltr; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            body { min-height: max(884px, 100dvh); }
        </style>
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-surface font-body text-on-surface min-h-screen flex flex-col overflow-x-hidden">
        <header class="bg-[#fbf9f5] sticky top-0 z-50 w-full mb-6 relative">
            <div class="flex justify-between items-center px-6 py-4 w-full max-w-7xl mx-auto">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-2xl" data-icon="account_tree">account_tree</span>
                    <h1 class="font-headline font-extrabold text-lg tracking-tighter text-primary">TrahKita</h1>
                </a>
            </div>
            <div class="bg-[#f2efe9] h-[1px] w-full"></div>
        </header>

        <main class="flex-grow flex flex-col items-center w-full px-4 mb-20">
            <!-- Content -->
            <div class="w-full max-w-4xl">
                {{ $slot }}
            </div>
        </main>
        
        <footer class="bg-[#fbf9f5] border-t border-[#f2efe9]/50 w-full mt-auto">
            <div class="w-full py-12 px-8 flex flex-col items-center gap-2 max-w-7xl mx-auto">
                <div class="font-headline font-semibold text-primary text-xl">TrahKita</div>
                <p class="font-body text-sm leading-relaxed text-secondary">© 2026 Dhirapratama Project</p>
            </div>
        </footer>
    </body>
</html>
