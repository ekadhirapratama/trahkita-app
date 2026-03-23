<x-public-layout>
    <div x-data="searchComponent()" class="w-full flex flex-col items-center">
        <!-- Hero Section -->
        <section class="w-full pt-10 pb-12 flex flex-col items-center text-center bg-gradient-to-b from-surface to-surface-container-low rounded-3xl border border-outline-variant/20 shadow-sm px-6">
            <h2 class="font-headline font-extrabold text-3xl md:text-5xl text-primary leading-tight mb-8 max-w-2xl tracking-tight min-h-[4rem] flex items-center justify-center">
                <span x-text="currentCta"></span><span class="animate-pulse">|</span>
            </h2>
            
            <div class="w-full max-w-2xl relative">
                <!-- Search Input Group -->
                <div class="flex flex-col gap-4 w-full">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-outline" data-icon="search">search</span>
                        </div>
                        <input 
                            type="text" 
                            x-model="query"
                            @input.debounce.500ms="fetchResults"
                            @keydown.escape="showDropdown = false"
                            @focus="showDropdown = true; if(query.length >= 2) fetchResults()"
                            class="w-full pl-14 pr-12 py-5 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl text-[18px] md:text-[20px] font-medium text-on-surface shadow-xl shadow-primary/5 focus:ring-4 focus:ring-primary/10 transition-all placeholder:text-outline/60 focus:border-primary/30"
                            placeholder="Cari nama kakek, nenek, atau sepupu..."
                            autocomplete="off"
                        >
                        
                        <!-- Loading Spinner -->
                        <div x-show="isLoading" class="absolute right-5 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Real-time Suggestion Dropdown -->
                <div 
                    x-show="showDropdown && (query.length >= 2)"
                    x-transition.opacity
                    @click.away="showDropdown = false"
                    class="absolute top-[84px] left-0 right-0 z-40 bg-surface-container-lowest rounded-2xl shadow-2xl overflow-hidden mt-2 text-left border border-outline-variant/20"
                    style="display: none;"
                >
                    <!-- Results List -->
                    <div x-show="results.length > 0" class="max-h-96 overflow-y-auto">
                        <template x-for="item in results" :key="item.id">
                            <a :href="'/member/' + item.id" class="p-4 flex items-center gap-4 border-b border-surface-container hover:bg-surface-container-low transition-colors cursor-pointer last:border-none block group">
                                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold shadow-inner">
                                    <template x-if="item.photo_path">
                                        <img :src="item.photo_path" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.photo_path">
                                        <span class="text-lg tracking-widest" x-text="item.initials"></span>
                                    </template>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-bold text-on-surface group-hover:text-primary transition-colors" x-text="item.full_name"></span>
                                        <template x-if="item.nickname">
                                            <span class="text-sm text-secondary font-medium" x-text="'(' + item.nickname + ')'"></span>
                                        </template>
                                    </div>
                                    <p class="text-sm text-on-surface-variant" x-text="item.context"></p>
                                </div>
                            </a>
                        </template>
                    </div>

                    <!-- Empty state -->
                    <div x-show="!isLoading && results.length === 0" class="px-5 py-8 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-3 text-outline/50 block" data-icon="search_off">search_off</span>
                        Belum ada data untuk pencarian ini.
                    </div>
                </div>
            </div>
            
        </section>

        <!-- Inspiration Ticker -->
        <section class="py-12 bg-surface-container-low overflow-hidden w-full mt-8 rounded-3xl border border-outline-variant/20">
            <p class="text-center text-secondary font-medium text-sm tracking-widest uppercase mb-6 opacity-70">Anggota Keluarga Terdata</p>
            <div class="flex whitespace-nowrap" style="animation: scroll-left 30s linear infinite;">
                <div class="flex gap-4 px-4">
                    @foreach($randomMembers as $m)
                        <span class="px-6 py-2 rounded-full bg-surface-container-lowest text-primary font-medium border border-outline-variant/30 shadow-sm">{{ $m->full_name }}</span>
                    @endforeach
                </div>
                <!-- Duplicate for seamless scroll if randomMembers are few, we just loop it multiple times -->
                <div class="flex gap-4 px-4">
                    @foreach($randomMembers as $m)
                        <span class="px-6 py-2 rounded-full bg-surface-container-lowest text-primary font-medium border border-outline-variant/30 shadow-sm">{{ $m->full_name }}</span>
                    @endforeach
                </div>
                <div class="flex gap-4 px-4">
                    @foreach($randomMembers as $m)
                        <span class="px-6 py-2 rounded-full bg-surface-container-lowest text-primary font-medium border border-outline-variant/30 shadow-sm">{{ $m->full_name }}</span>
                    @endforeach
                </div>
                <div class="flex gap-4 px-4">
                    @foreach($randomMembers as $m)
                        <span class="px-6 py-2 rounded-full bg-surface-container-lowest text-primary font-medium border border-outline-variant/30 shadow-sm">{{ $m->full_name }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <!-- Script for Alpine Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchComponent', () => ({
                query: '',
                results: [],
                isLoading: false,
                showDropdown: false,
                
                // CTA Text Animation Data
                ctas: [
                    "Temukan Kembali Akar Keluarga Kita",
                    "Cari nama anggota keluarga yang ingin Bapak/Ibu temukan...",
                    "Temukan silsilah dan jejak langkah keluarga kita",
                    "Ingat nama panggilannya? Coba ketik di sini.",
                    "Siapa nama kakek buyutmu?",
                    "Sudah kenal dengan sedulur dari Generasi ke-4?"
                ],
                currentCta: '',
                ctaIdx: 0,
                charIdx: 0,
                isDeleting: false,
                
                init() {
                    this.typeEffect();
                },

                typeEffect() {
                    const currentTxt = this.ctas[this.ctaIdx];
                    
                    if(this.isDeleting) {
                        this.charIdx--;
                    } else {
                        this.charIdx++;
                    }

                    this.currentCta = currentTxt.substring(0, this.charIdx);

                    let typeSpeed = this.isDeleting ? 40 : 80;

                    if(!this.isDeleting && this.charIdx === currentTxt.length) {
                        typeSpeed = 3000; // Pause at end
                        this.isDeleting = true;
                    } else if(this.isDeleting && this.charIdx === 0) {
                        this.isDeleting = false;
                        this.ctaIdx = (this.ctaIdx + 1) % this.ctas.length;
                        typeSpeed = 500; // Pause before next word
                    }

                    setTimeout(() => this.typeEffect(), typeSpeed);
                },

                fetchResults() {
                    if(this.query.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.isLoading = true;
                    fetch('/api/search?q=' + encodeURIComponent(this.query))
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                            this.isLoading = false;
                            this.showDropdown = true;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                }
            }))
        })
    </script>
    <style>
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
</x-public-layout>