@extends('layouts.app')

@section('title', 'Login - Cinebox')

@section('content')
<nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="/">
            <img src="{{ asset('Logo.png') }}" alt="Logo" class="h-8">
        </a>
        <div class="space-x-4">
            <a href="/register" class="text-sm font-bold text-tealbrand hover:text-teal-700 transition-colors">Daftar Akun</a>
        </div>
    </div>
</nav>

<div class="min-h-[90vh] flex flex-col items-center justify-center px-4 bg-gray-50">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('Logo.png') }}" alt="Cinebox" class="h-12 mx-auto mb-2">
            <p class="text-gray-500 text-sm font-medium">Selamat datang kembali di Cinebox!</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-2xl shadow-gray-200/50 border border-gray-100">
            <h2 class="text-3xl font-black text-gray-800 mb-8 text-center tracking-tight">Masuk</h2>

            <form id="login-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 focus:border-tealbrand outline-none transition-all placeholder-gray-400"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-tealbrand/20 focus:border-tealbrand outline-none transition-all placeholder-gray-400"
                        placeholder="••••••••">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-[#06746C] hover:bg-[#00F2E0] text-white hover:text-gray-900 font-black text-lg py-4 rounded-2xl shadow-xl transition-all duration-300 transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                        <span id="btn-text">Masuk Sekarang</span>
                    </button>
                </div>
            </form>

            <div id="err" class="text-red-500 text-sm mt-4 text-center font-bold"></div>

            <div class="mt-8 text-center border-t border-gray-50 pt-6">
                <p class="text-gray-600 text-sm font-medium">
                    Belum punya akun?
                    <a href="/register" class="text-tealbrand font-black hover:underline ml-1">Daftar Gratis</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showToast(msg, type = 'info'){
        const t = document.getElementById('toast');
        if(!t) return;
        t.innerText = msg;
        t.className = 'fixed bottom-6 right-6 p-4 rounded-xl shadow-2xl text-white font-semibold z-50 transition-all animate-bounce ' + (type=='error' ? 'bg-red-600' : 'bg-tealbrand');
        t.style.display = 'block';
        setTimeout(()=> t.style.display = 'none', 3500);
    }

    document.getElementById('login-form').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button');
        const btnText = document.getElementById('btn-text');

        btn.disabled = true;
        const oldText = btnText.innerText;
        btnText.innerText = 'Signing in...';
        document.getElementById('err').innerText = '';

        // Mengambil data dari name attribute
        const data = {
            email: form.email.value,
            password: form.password.value
        };

        try {
            const res = await axios.post('/api/login', data);

            // Simpan token ke localStorage (fungsi setToken harus ada di layouts.app)
            const token = res.data.access_token || res.data.token;
            if (typeof setToken === "function") {
                setToken(token);
            } else {
                localStorage.setItem('token', token);
            }

            showToast('Login successful', 'success');

            // Redirect ke dashboard
            window.location.href = '/dashboard';
        } catch (err) {
            const msg = err.response?.data?.message || 'Login failed. Check your credentials.';
            document.getElementById('err').innerText = msg;
            showToast(msg, 'error');
        } finally {
            btn.disabled = false;
            btnText.innerText = oldText;
        }
    });
</script>
@endpush
