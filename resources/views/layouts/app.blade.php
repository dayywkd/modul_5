<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bioskop Dashboard')</title>
    <!-- Tailwind CDN for quick setup -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-lg font-semibold">Bioskop Dashboard</div>
            <div class="space-x-4">
                <a href="/" class="text-gray-600">Home</a>
                <a href="/dashboard" id="nav-dashboard" class="text-gray-600">Dashboard</a>
                <a href="/login" id="nav-login" class="text-gray-600">Login</a>
                <a href="/register" id="nav-register" class="text-gray-600">Register</a>
                <button id="btn-logout" class="hidden text-red-500">Logout</button>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6">
        @yield('content')
    </main>

    <div id="toast" style="display:none"></div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Small auth helper to manage token/UI
        function setToken(token) {
            if (token) {
                localStorage.setItem('jwt_token', token);
                window.axios.defaults = window.axios.defaults || {};
                window.axios.defaults.headers = window.axios.defaults.headers || {};
                window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                document.getElementById('nav-login').style.display = 'none';
                document.getElementById('nav-register').style.display = 'none';
                document.getElementById('btn-logout').style.display = 'inline';
            } else {
                localStorage.removeItem('jwt_token');
                if (window.axios.defaults && window.axios.defaults.headers && window.axios.defaults.headers.common) {
                    delete window.axios.defaults.headers.common['Authorization'];
                }
                document.getElementById('nav-login').style.display = 'inline';
                document.getElementById('nav-register').style.display = 'inline';
                document.getElementById('btn-logout').style.display = 'none';
            }
        }

        // Initialize from storage
        (function(){
            const t = localStorage.getItem('jwt_token');
            if (t) setToken(t);
        })();

        function showToast(msg, type = 'info'){
            const t = document.getElementById('toast');
            t.innerText = msg;
            t.className = 'fixed bottom-6 right-6 p-3 rounded shadow text-white ' + (type=='error' ? 'bg-red-600' : 'bg-green-600');
            t.style.display = 'block';
            setTimeout(()=> t.style.display = 'none', 3500);
        }

        // If token exists in localStorage, also set axios header
        (function(){
            const t = localStorage.getItem('jwt_token');
            if (t && window.axios && window.axios.defaults && window.axios.defaults.headers) {
                window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
            }
        })();

        document.getElementById('btn-logout').addEventListener('click', async function(){
            const token = localStorage.getItem('jwt_token');
            if (!token) return setToken(null);
            try {
                await axios.post('/api/logout', {}, { headers: { Authorization: 'Bearer ' + token } });
            } catch (e) { /* ignore */ }
            setToken(null);
            window.location = '/';
        });
    </script>
</body>
</html>
