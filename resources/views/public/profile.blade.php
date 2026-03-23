<x-public-layout>
    <!-- Top Navigation Anchor (Specific for Profile) -->
    <div class="flex items-center justify-between w-full mb-6 relative">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="flex items-center text-primary active:scale-95 duration-200 p-2 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
            <span class="font-headline font-bold text-sm ml-2 hidden sm:inline">Kembali</span>
        </a>
    </div>

    <!-- Hero Section: The Curator's Choice -->
    <section class="mt-4 flex flex-col items-center text-center">
        <div class="relative group">
            <!-- Tonal layering for the frame -->
            <div class="absolute -inset-1 bg-gradient-to-tr from-primary to-tertiary-container rounded-[2rem] blur opacity-20"></div>
            <div class="relative w-40 h-40 sm:w-48 sm:h-48 rounded-[1.75rem] overflow-hidden border-4 border-surface-container-lowest shadow-xl bg-surface-container flex items-center justify-center">
                @if($member->photo_path)
                    <img src="{{ asset('storage/' . $member->photo_path) }}" class="w-full h-full object-cover" alt="{{ $member->full_name }}">
                @else
                    @php
                        $initials = collect(explode(' ', $member->full_name))
                            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                            ->take(2)->join('');
                    @endphp
                    <span class="text-5xl font-bold tracking-widest text-primary/50">{{ $initials }}</span>
                @endif
            </div>
        </div>
        
        <div class="mt-6 space-y-1">
            <h2 class="font-headline font-black text-3xl tracking-tight text-primary">{{ $member->full_name }}</h2>
            @if($member->nickname)
                <p class="font-body text-secondary font-medium text-lg tracking-wide uppercase">({{ $member->nickname }})</p>
            @endif
        </div>
        
        <!-- Lineage Badge -->
        <div class="mt-4 inline-flex items-center px-4 py-2 bg-primary-container/10 rounded-full">
            <span class="material-symbols-outlined text-primary text-sm mr-2" data-icon="account_tree">account_tree</span>
            <span class="font-body text-on-primary-fixed-variant text-sm font-semibold italic">
                @if($member->father && $member->mother)
                    Anak dari {{ $member->father->full_name }} &amp; {{ $member->mother->full_name }}
                @elseif($member->father)
                    Anak dari {{ $member->father->full_name }}
                @elseif($member->mother)
                    Anak dari {{ $member->mother->full_name }}
                @else
                    Generasi ke-{{ $member->generation }}
                @endif
            </span>
        </div>
    </section>

    <!-- Information Partition (Editorial Spacing) -->
    <div class="mt-12 space-y-10 w-full mb-12">
        <!-- Pasangan Section -->
        @if($member->marriagesAsHusband->count() > 0 || $member->marriagesAsWife->count() > 0)
        <section>
            <h3 class="font-headline font-bold text-xl text-primary mb-4 flex items-center">
                <span class="material-symbols-outlined mr-2 text-tertiary" data-icon="favorite">favorite</span>
                Pasangan
            </h3>
            <div class="space-y-4">
                @foreach($member->marriagesAsHusband as $m)
                    <a href="{{ route('member.profile', $m->wife->id) }}" class="bg-surface-container-lowest rounded-xl p-4 flex items-center shadow-sm border border-outline-variant/10 active:scale-[0.98] transition-all hover:bg-surface-container-low cursor-pointer block group">
                        <div class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0 border-2 border-surface-container-high bg-secondary-container flex items-center justify-center">
                            @if($m->wife->photo_path)
                                <img src="{{ asset('storage/' . $m->wife->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-on-secondary-container text-sm">{{ substr($m->wife->full_name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-4 flex-grow">
                            <p class="font-headline font-bold text-on-surface group-hover:text-primary transition-colors">{{ $m->wife->full_name }}</p>
                            <p class="font-body text-on-surface-variant text-sm">Istri</p>
                        </div>
                        <span class="material-symbols-outlined text-outline" data-icon="chevron_right">chevron_right</span>
                    </a>
                @endforeach
                @foreach($member->marriagesAsWife as $m)
                    <a href="{{ route('member.profile', $m->husband->id) }}" class="bg-surface-container-lowest rounded-xl p-4 flex items-center shadow-sm border border-outline-variant/10 active:scale-[0.98] transition-all hover:bg-surface-container-low cursor-pointer block group">
                        <div class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0 border-2 border-surface-container-high bg-secondary-container flex items-center justify-center">
                            @if($m->husband->photo_path)
                                <img src="{{ asset('storage/' . $m->husband->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-on-secondary-container text-sm">{{ substr($m->husband->full_name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-4 flex-grow">
                            <p class="font-headline font-bold text-on-surface group-hover:text-primary transition-colors">{{ $m->husband->full_name }}</p>
                            <p class="font-body text-on-surface-variant text-sm">Suami</p>
                        </div>
                        <span class="material-symbols-outlined text-outline" data-icon="chevron_right">chevron_right</span>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Anak-anak Section -->
        @if($member->children->count() > 0)
        <section>
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-headline font-bold text-xl text-primary flex items-center">
                    <span class="material-symbols-outlined mr-2 text-tertiary" data-icon="group">group</span>
                    Anak-anak
                </h3>
                <span class="bg-surface-container-high text-on-surface-variant text-xs font-bold px-3 py-1.5 rounded-md">{{ $member->children->count() }} ORANG</span>
            </div>
            <div class="space-y-4">
                @foreach($member->children as $child)
                    <a href="{{ route('member.profile', $child->id) }}" class="bg-surface-container-lowest rounded-xl p-4 flex items-center shadow-sm border border-outline-variant/10 active:scale-[0.98] transition-all hover:bg-surface-container-low cursor-pointer block group">
                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-surface-container flex items-center justify-center">
                            @if($child->photo_path)
                                <img src="{{ asset('storage/' . $child->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-bold text-outline text-xs">{{ substr($child->full_name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="ml-4 flex-grow">
                            <p class="font-headline font-semibold text-on-surface group-hover:text-primary transition-colors">{{ $child->full_name }}</p>
                        </div>
                        <span class="material-symbols-outlined text-outline" data-icon="chevron_right">chevron_right</span>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    <!-- Primary Action: High-Contrast & Accessible -->
    <div class="mt-4 mb-8 w-full flex justify-center">
        <button class="w-full max-w-sm bg-primary text-on-primary font-headline font-bold py-4 px-6 rounded-2xl flex items-center justify-center shadow-lg active:scale-95 transition-transform hover:bg-primary-container">
            <span class="material-symbols-outlined mr-3" data-icon="edit">edit</span>
            Ajukan Perubahan
        </button>
    </div>
</x-public-layout>
