@extends('layouts.app')

@section('title','Register')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Register</h2>
    <form id="register-form" class="space-y-4">
        <div>
            <label class="block text-sm">Name</label>
            <input name="name" type="text" required class="w-full border p-2 rounded" />
        </div>
        <div>
            <label class="block text-sm">Email</label>
            <input name="email" type="email" required class="w-full border p-2 rounded" />
        </div>
        <div>
            <label class="block text-sm">Password</label>
            <input name="password" type="password" required class="w-full border p-2 rounded" />
        </div>
        <div>
            <label class="block text-sm">Confirm Password</label>
            <input name="password_confirmation" type="password" required class="w-full border p-2 rounded" />
        </div>
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Register</button>
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

    document.getElementById('register-form').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button');
        btn.disabled = true;
        const oldText = btn.innerText;
        btn.innerText = 'Registering...';
        document.getElementById('err').innerText = '';

        const data = { name: form.name.value, email: form.email.value, password: form.password.value, password_confirmation: form.password_confirmation.value };
        try {
            const res = await axios.post('/api/register', data);
            const token = res.data.access_token || res.data.token || res.data;
            setToken(token);
            showToast('Register successful', 'success');
            window.location = '/dashboard';
        } catch (err) {
            const errors = err.response?.data?.errors;
            if (errors) {
                document.getElementById('err').innerText = Object.values(errors).map(v=>v[0]).join(', ');
                showToast(Object.values(errors).map(v=>v[0]).join(', '), 'error');
            } else {
                const msg = JSON.stringify(err.response?.data || 'Register failed');
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
