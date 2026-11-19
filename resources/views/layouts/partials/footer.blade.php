<footer class="bg-white border-t border-gray-100 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Logo & Tagline --}}
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#2C99D6">
                    <span class="text-white font-black text-xs">S</span>
                </div>
                <div>
                    <p class="text-sm font-black tracking-tight text-gray-900">SKENA</p>
                    <p class="text-xs text-gray-400">Marketplace Jasa Kreatif Mahasiswa</p>
                </div>
            </div>
            {{-- Links --}}
            <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                <a href="#" class="hover:text-brand-blue transition-colors">Tentang SKENA</a>
                <a href="#" class="hover:text-brand-blue transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-brand-blue transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-brand-blue transition-colors">Hubungi Kami</a>
            </div>
            {{-- Copyright --}}
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} SKENA. All rights reserved.</p>
        </div>
    </div>
</footer>
