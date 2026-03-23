<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center justify-center p-8 border-t-4 border-blue-500">
                    <span class="text-gray-500 font-semibold mb-2 uppercase tracking-wide text-sm">Total Anggota Keluarga</span>
                    <span class="text-4xl font-bold text-gray-800">{{ number_format($totalMembers) }}</span>
                    <a href="{{ route('admin.members.index') }}" class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">Kelola Anggota &raquo;</a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col items-center justify-center p-8 border-t-4 border-yellow-500 relative">
                    <span class="text-gray-500 font-semibold mb-2 uppercase tracking-wide text-sm">Pengajuan Menunggu (Pending)</span>
                    <span class="text-4xl font-bold text-gray-800">{{ number_format($pendingSubmissions) }}</span>
                    
                    @if($pendingSubmissions > 0)
                        <span class="absolute top-4 right-4 flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                        </span>
                    @endif
                    
                    <a href="{{ route('admin.submissions.index', ['status' => 'pending']) }}" class="mt-4 text-yellow-600 hover:text-yellow-800 text-sm font-medium hover:underline">Tinjau Pengajuan &raquo;</a>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-500">history</span> Aksi Terakhir Administrator
                    </h3>
                    
                    <div class="space-y-4">
                        @if($latestActivities->isNotEmpty())
                            @foreach($latestActivities as $activity)
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 shrink-0 font-bold uppercase text-xl">
                                    {{ substr(optional($activity->user)->name ?? 'S', 0, 1) }}
                                </div>
                                <div class="flex-grow pt-0.5">
                                    <p class="text-sm text-gray-800 font-medium">
                                        <span class="font-bold border-b border-dotted border-gray-400">{{ optional($activity->user)->name ?? 'System' }}</span> 
                                        {{ $activity->action }}
                                        <span class="text-gray-500">(Target ID: {{ $activity->target_id }})</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }} - {{ $activity->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-200 rounded-xl">
                                Belum ada aktivitas yang terekam di dalam sistem log aksi.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
