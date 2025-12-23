@extends('layouts.app')

@section('title', 'Dashboard - Cinebox')

@section('content')
<nav class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-40 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-4">
                <a href="/dashboard" class="flex items-center gap-2 group">
                    <img src="{{ asset('Logo.png') }}" alt="Logo" class="h-8 transition-transform group-hover:scale-105">
                </a>
                <div class="hidden md:block h-6 w-px bg-gray-200 mx-2"></div>
                <span id="dashboard-type-text" class="hidden md:block text-sm font-medium text-gray-400 italic">User Dashboard</span>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <a href="/" class="flex items-center gap-1 px-4 py-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-[#09A79A] transition-all font-bold text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="hidden sm:inline">Home</span>
                </a>

                <div class="flex flex-col items-end hidden sm:flex leading-tight">
                    <span id="user-display-name" class="text-sm font-bold text-gray-800">Memuat...</span>
                    <span id="user-role-text" class="text-[10px] font-extrabold text-[#09A79A] uppercase tracking-widest">User</span>
                </div>

                <div class="h-10 w-10 rounded-full bg-teal-50 border-2 border-[#09A79A]/20 flex items-center justify-center text-[#09A79A] font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <button id="btn-logout-final" class="ml-2 p-2 text-gray-400 hover:text-red-500 transition-colors cursor-pointer" title="Keluar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="h-24"></div>

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Dashboard</h1>
        <p class="text-gray-500 mt-1">Kelola request film secara real-time.</p>
    </div>
    <button id="btn-new" class="bg-[#09A79A] text-white px-8 py-3.5 rounded-2xl font-bold shadow-xl hover:bg-[#06746C] transition flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Request Film Baru
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-50">
            <h3 class="font-black text-gray-800 text-lg mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-[#09A79A] rounded-full"></span>
                Kategori
            </h3>
            <ul id="categories-list" class="space-y-3"></ul>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div id="tickets-list" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"></div>
    </div>
</div>

<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-md p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg p-8 relative">
        <button onclick="closeModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h3 id="modal-title" class="text-2xl font-black text-gray-800 mb-6">Request Film</h3>
        <form id="ticket-form" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Judul Film</label>
                <input name="movie_title" required class="w-full bg-gray-50 border-none px-5 py-3.5 rounded-2xl focus:ring-2 focus:ring-[#09A79A] outline-none" />
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="category_select" required class="w-full bg-gray-50 border-none px-5 py-3.5 rounded-2xl outline-none"></select>
            </div>

            <div id="status-group" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-1">Status (Admin Only)</label>
                <select name="status" id="status_field" class="w-full bg-gray-50 border-none px-5 py-3.5 rounded-2xl outline-none font-bold">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="in procces">In Procces</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-gray-50 border-none px-5 py-3.5 rounded-2xl outline-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Poster</label>
                <input type="file" name="file" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-[#09A79A] font-bold"/>
            </div>
            <div class="flex justify-end pt-4 gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-2xl text-gray-400 font-bold">Batal</button>
                <button type="submit" class="bg-[#09A79A] text-white px-10 py-3 rounded-2xl font-black">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const jwtToken = localStorage.getItem('jwt_token') || localStorage.getItem('token');
    if (!jwtToken) window.location = '/login';

    axios.defaults.headers.common['Authorization'] = `Bearer ${jwtToken}`;

    // Logout
    const logoutBtn = document.getElementById('btn-logout-final');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            if (confirm('Yakin ingin keluar?')) {
                localStorage.removeItem('jwt_token');
                localStorage.removeItem('token');
                window.location = '/login';
            }
        });
    }

    // Load User Data
    async function loadUser() {
        try {
            const res = await axios.get('/api/me');
            document.getElementById('user-display-name').innerText = res.data.name;
            const isAdmin = res.data.email === 'admin@example.com';
            document.getElementById('user-role-text').innerText = isAdmin ? 'Administrator' : 'User';
            document.getElementById('dashboard-type-text').innerText = isAdmin ? 'Admin Dashboard' : 'User Dashboard';
            window.userIsAdmin = isAdmin; // Simpan status admin secara global
        } catch (e) { window.location = '/login'; }
    }

    // Load Tickets
    async function loadTickets() {
        const container = document.getElementById('tickets-list');
        container.innerHTML = '<p class="col-span-full text-center">Memuat...</p>';
        try {
            const res = await axios.get('/api/tickets');
            container.innerHTML = '';
            res.data.data.forEach(t => {
                const imgPath = t.file_path ? `/storage/${t.file_path}` : 'https://via.placeholder.com/400x600?text=Cinebox';

                // Perbaikan warna status UI
                let sClass = 'bg-yellow-50 text-yellow-600';
                if(t.status === 'approved') sClass = 'bg-green-50 text-green-600';
                if(t.status === 'in procces') sClass = 'bg-blue-50 text-blue-600';
                if(t.status === 'rejected') sClass = 'bg-red-50 text-red-600';

                container.innerHTML += `
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-lg border border-gray-50 flex flex-col">
                    <div class="h-48 bg-gray-200">
                        <img src="${imgPath}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x600?text=Poster+Error'">
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <span class="text-[10px] font-black text-[#09A79A] uppercase mb-1">${t.category?.name || 'Film'}</span>
                        <h3 class="font-bold text-gray-800 mb-2">${t.movie_title}</h3>
                        <div class="mb-4"><span class="text-[9px] font-black px-2 py-1 rounded-lg border ${sClass} uppercase">${t.status}</span></div>
                        <div class="mt-auto flex justify-between border-t pt-4">
                            <button class="text-[10px] font-black text-gray-400 hover:text-[#09A79A] uppercase" onclick="editTicket('${t.slug}')">Detail</button>
                            <button class="text-[10px] font-black text-gray-300 hover:text-red-500 uppercase" onclick="deleteTicket('${t.slug}')">Hapus</button>
                        </div>
                    </div>
                </div>`;
            });
        } catch(e) { container.innerHTML = 'Gagal memuat data.'; }
    }

    window.editTicket = async (slug) => {
        const res = await axios.get(`/api/tickets/${slug}`);
        openModal('update', res.data);
    };

    window.deleteTicket = async (slug) => {
        if(confirm('Hapus?')) {
            await axios.delete(`/api/tickets/${slug}`);
            loadTickets();
        }
    };

    const modal = document.getElementById('modal');
    const form = document.getElementById('ticket-form');

    function openModal(mode, data = null) {
        form.reset();
        form.dataset.action = mode;
        const statusGrp = document.getElementById('status-group');

        if(mode === 'update') {
            // Dropdown status hanya muncul jika user adalah Admin
            if(window.userIsAdmin) {
                statusGrp.classList.remove('hidden');
            } else {
                statusGrp.classList.add('hidden');
            }

            form.movie_title.value = data.movie_title;
            form.description.value = data.description;
            form.category_id.value = data.category_id;
            document.getElementById('status_field').value = data.status;
            form.dataset.slug = data.slug;
            document.getElementById('modal-title').innerText = 'Edit Request';
        } else {
            // Sembunyikan status saat membuat request baru
            statusGrp.classList.add('hidden');
            document.getElementById('modal-title').innerText = 'Request Film Baru';
        }
        modal.classList.replace('hidden', 'flex');
    }

    window.closeModal = () => modal.classList.replace('flex', 'hidden');

    form.onsubmit = async (e) => {
        e.preventDefault();
        const fd = new FormData(form);
        const action = form.dataset.action;

        if(action === 'update') {
            fd.append('_method', 'PUT');
            // Pastikan nilai status ikut terkirim jika admin mengedit
            if(window.userIsAdmin) {
                fd.append('status', document.getElementById('status_field').value);
            }
        }

        try {
            const url = action === 'create' ? '/api/tickets' : `/api/tickets/${form.dataset.slug}`;
            await axios.post(url, fd);
            closeModal();
            loadTickets();
        } catch(e) {
            alert('Gagal menyimpan: ' + (e.response?.data?.message || 'Error'));
        }
    };

    // Initialize
    loadUser();
    loadTickets();

    // Categories
    axios.get('/api/categories').then(res => {
        const sel = document.getElementById('category_select');
        const list = document.getElementById('categories-list');
        res.data.forEach(c => {
            sel.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            list.innerHTML += `<li class="flex justify-between text-sm font-bold text-gray-600"><span>${c.name}</span><span class="text-[#09A79A]">${c.request_count}</span></li>`;
        });
    });

    document.getElementById('btn-new').onclick = () => openModal('create');
</script>
@endpush
@endsection
