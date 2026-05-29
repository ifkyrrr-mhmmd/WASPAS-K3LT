<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WASPAS - K3LT - Seleksi Kepala Divisi K3LT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Premium Gradients */
        .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 40% 20%, hsla(253,16%,7%,1) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(189,100%,56%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(355,100%,93%,0) 0px, transparent 50%),
                radial-gradient(at 80% 50%, hsla(340,100%,76%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(22,100%,77%,0) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(242,100%,70%,0.15) 0px, transparent 50%),
                radial-gradient(at 0% 0%, hsla(343,100%,76%,0) 0px, transparent 50%);
        }
        
        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        /* Floating Animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float 6s ease-in-out 3s infinite;
        }
        
        /* Glow text */
        .text-glow {
            text-shadow: 0 0 40px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen text-slate-200 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card border-b-0 border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                    <a href="/" class="flex items-center">
                        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@800&display=swap" rel="stylesheet">
                        <span style="font-family: 'Plus Jakarta Sans', sans-serif;" class="text-2xl font-extrabold tracking-tight text-white">
                            WASPAS - <span class="text-emerald-400">K3LT</span>
                        </span>
                    </a>
                
                <div class="flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors duration-200">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-sm font-medium text-white transition-all duration-300 hover:shadow-[0_0_20px_rgba(255,255,255,0.1)]">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-500/20 rounded-full blur-[120px] opacity-50 mix-blend-screen pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-cyan-500/20 rounded-full blur-[100px] opacity-40 mix-blend-screen pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card mb-8 text-sm text-cyan-300 font-medium">
                    <span class="flex h-2 w-2 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                    </span>
                    Sistem Pendukung Keputusan K3LT
                </div>
                
                <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight text-white mb-8 leading-tight">
                    Seleksi Kepala Divisi <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-400 text-glow">Platform WASPAS - K3LT</span>
                </h1>
                
                <p class="mt-4 text-lg sm:text-xl text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Platform cerdas yang dirancang khusus untuk menganalisis, memeringkat, dan memilih kandidat terbaik dengan algoritma Weighted Aggregated Sum Product Assessment secara presisi.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="group relative px-8 py-4 bg-white text-slate-900 rounded-full font-bold text-lg hover:scale-105 transition-all duration-300 shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:shadow-[0_0_60px_rgba(255,255,255,0.5)] flex items-center gap-2">
                            Masuk ke Dashboard
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-gradient-to-r from-indigo-500 to-cyan-500 text-white rounded-full font-bold text-lg hover:scale-105 transition-all duration-300 shadow-[0_0_40px_rgba(99,102,241,0.4)] flex items-center gap-2">
                            Mulai Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="#features" class="px-8 py-4 rounded-full glass-card font-medium text-white hover:bg-white/10 transition-colors duration-300">
                            Pelajari Fitur
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Floating UI Elements to wow the user -->
        <div class="hidden lg:block absolute top-40 left-10 w-64 glass-card rounded-2xl p-4 animate-float opacity-80 rotate-[-5deg]">
            <div class="flex items-center justify-between mb-4">
                <div class="text-xs text-slate-400">Normalisasi Q1</div>
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
            </div>
            <div class="h-2 bg-white/10 rounded-full w-full mb-3"><div class="h-full bg-indigo-500 rounded-full w-[85%]"></div></div>
            <div class="h-2 bg-white/10 rounded-full w-full mb-3"><div class="h-full bg-cyan-500 rounded-full w-[70%]"></div></div>
            <div class="h-2 bg-white/10 rounded-full w-full"><div class="h-full bg-emerald-500 rounded-full w-[92%]"></div></div>
        </div>
        
        <div class="hidden lg:block absolute bottom-20 right-10 w-72 glass-card rounded-2xl p-5 animate-float-delayed opacity-80 rotate-[3deg]">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-full bg-yellow-400/20 flex items-center justify-center text-yellow-400 font-bold text-xl border border-yellow-400/30">1</div>
                <div>
                    <div class="font-bold text-white text-sm">Budi Santoso</div>
                    <div class="text-xs text-indigo-300 mt-1">Skor Qi: 0.9482</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 relative z-10 border-t border-white/5 bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Kenapa Menggunakan Aplikasi Ini?</h2>
                <p class="text-slate-400 max-w-2xl mx-auto">Kami mengemas kompleksitas algoritma matematika ke dalam antarmuka yang sangat indah dan mudah digunakan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 flex items-center justify-center mb-6 border border-indigo-500/30 group-hover:bg-indigo-500/40 transition-colors">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Manajemen Kriteria Dinamis</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Atur bobot dan tipe atribut (Benefit/Cost) dengan leluasa. Sistem otomatis menyesuaikan matriks tanpa perlu pusing mengubah kode.
                    </p>
                </div>
                
                <!-- Card 2 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 flex items-center justify-center mb-6 border border-cyan-500/30 group-hover:bg-cyan-500/40 transition-colors">
                        <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Mesin Kalkulasi Cepat</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Proses perhitungan SAW dan WP terintegrasi penuh. Dapatkan hasil akhir Qi dan perankingan secara transparan dan kilat.
                    </p>
                </div>
                
                <!-- Card 3 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-6 border border-emerald-500/30 group-hover:bg-emerald-500/40 transition-colors">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Jejak Histori & Audit</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Setiap proses perhitungan disimpan dengan aman ke dalam riwayat, lengkap dengan log audit siapa yang melakukan perubahan.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="py-8 border-t border-white/10 text-center text-slate-500 text-sm">
        <p>&copy; 2026 WASPAS - K3LT - Keputusan Seleksi Kepala Divisi K3LT yang Objektif & Presisi.</p>
    </footer>

</body>
</html>
