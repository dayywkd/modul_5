@extends('layouts.app')

@section('title', 'Register - Cinebox')

@section('content')
<nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/">
            <img src="{{ asset('Logo.png') }}" alt="Logo" class="h-8">
        </a>
        <div class="space-x-4">
            <a href="/login" class="text-sm font-bold text-tealbrand hover:text-teal-700 transition-colors">Sudah Punya Akun?</a>
        </div>
    </div>
</nav>

<div class="min-h-[90vh] flex flex-col items-center justify-center px-4 py-16 bg-gray-50">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('Logo.png') }}" alt="Cinebox" class="h-12 mx-auto mb-2">
            <p class="text-gray-500 text-sm font-medium">Buat akun untuk mulai menonton.</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-gray-100">
            <h2 class="text-3xl font-black text-gray-800 mb-8 text-center tracking-tight">Daftar</h2>

            <form id="register-form" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 focus:border-tealbrand outline-none transition-all"
                        placeholder="John Doe">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 focus:border-tealbrand outline-none transition-all"
                        placeholder="nama@email.com">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="py-4">
                    <button type="submit"
                        class="w-full bg-[#06746C] hover:bg-[#00F2E0] text-white hover:text-gray-900 font-black text-lg py-4 rounded-2xl shadow-xl transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                        <span id="btn-text">Daftar Sekarang</span>
                    </button>
                </div>
            </form>

            <div id="err" class="text-red-500 text-sm mt-4 text-center font-bold"></div>

            <div class="mt-8 text-center border-t border-gray-50 pt-6">
                <p class="text-gray-600 text-sm font-medium">
                    Sudah punya akun?
                    <a href="/login" class="text-tealbrand font-black hover:underline ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script tetap menggunakan logika Anda yang sudah berjalan
    function showToast(msg, type = 'info'){
        const t = document.getElementById('toast');
        if (!t) return;
        t.innerText = msg;
        t.className = 'fixed bottom-6 right-6 p-4 rounded-xl shadow-2xl text-white font-semibold z-50 transition-all animate-bounce ' + (type=='error' ? 'bg-red-500' : 'bg-tealbrand');
        t.style.display = 'block';
        setTimeout(()=> t.style.display = 'none', 4000);
    }

    document.getElementById('register-form').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button');

        btn.disabled = true;
        const oldText = btn.innerText;
        btn.innerText = 'Memproses...';
        document.getElementById('err').innerText = '';

        const data = {
            name: form.name.value,
            email: form.email.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value
        };

        try {
            const res = await axios.post('/api/register', data);
            const token = res.data.access_token || res.data.token || res.data;
            if (typeof setToken === "function") setToken(token);
            showToast('Pendaftaran Berhasil!', 'success');
            setTimeout(() => { window.location = '/dashboard'; }, 1000);
        } catch (err) {
            const errors = err.response?.data?.errors;
            if (errors) {
                const errorMsg = Object.values(errors).map(v=>v[0]).join(', ');
                document.getElementById('err').innerText = errorMsg;
                showToast(errorMsg, 'error');
            } else {
                const msg = err.response?.data?.message || 'Gagal mendaftar.';
                document.getElementById('err').innerText = msg;
                showToast(msg, 'error');
            }
        } finally {
            btn.disabled = false;
            btn.innerText = oldText;
        }
    });
</script>
@endpush
