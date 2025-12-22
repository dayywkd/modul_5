@extends('layouts.app')

@section('title','Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Login</h2>
    <form id="login-form" class="space-y-4">
        <div>
            <label class="block text-sm">Email</label>
            <input name="email" type="email" required class="w-full border p-2 rounded" />
        </div>
        <div>
            <label class="block text-sm">Password</label>
            <input name="password" type="password" required class="w-full border p-2 rounded" />
        </div>
        <div class="flex justify-end">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
        </div>
    </form>
    <div id="err" class="text-red-600 mt-3"></div>
</div>

<script>
    function showToast(msg, type = 'info'){
        const t = document.getElementById('toast');
        t.innerText = msg;
        t.className = 'fixed bottom-6 right-6 p-3 rounded shadow text-white ' + (type=='error' ? 'bg-red-600' : 'bg-green-600');
        t.style.display = 'block';
        setTimeout(()=> t.style.display = 'none', 3500);
    }

    document.getElementById('login-form').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button');
        btn.disabled = true;
        const oldText = btn.innerText;
        btn.innerText = 'Signing in...';
        document.getElementById('err').innerText = '';

        const data = { email: form.email.value, password: form.password.value };
        try {
            const res = await axios.post('/api/login', data);
            const token = res.data.access_token || res.data.token || res.data;
            setToken(token);
            showToast('Login successful', 'success');
            window.location = '/dashboard';
        } catch (err) {
            const errors = err.response?.data?.errors;
            if (errors) {
                // show first error
                const first = Object.values(errors)[0][0];
                document.getElementById('err').innerText = first;
                showToast(first, 'error');
            } else {
                const msg = err.response?.data?.message || 'Login failed';
                document.getElementById('err').innerText = msg;
                showToast(msg, 'error');
            }
        } finally {
            btn.disabled = false;
            btn.innerText = oldText;
        }
    });
</script>
@endsection
