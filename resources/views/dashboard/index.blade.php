@extends('layouts.app')

@section('title','Dashboard')

@section('content')
<script>
    if (!localStorage.getItem('jwt_token')) {
        window.location = '/login';
    }
</script>
<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2">
        <div class="bg-white p-4 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold">Tickets</h3>
                <button id="btn-new" class="bg-blue-600 text-white px-3 py-1 rounded">New</button>
            </div>
            <div id="tickets-list">Loading…</div>
        </div>
    </div>
    <div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold mb-2">Categories</h3>
            <ul id="categories-list" class="space-y-2"></ul>
        </div>
    </div>
</div>

<!-- Modal / form area -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">
    <div class="bg-white p-6 rounded w-1/2">
        <h3 id="modal-title" class="font-semibold mb-3">New Ticket</h3>
        <form id="ticket-form" class="space-y-3">
            <select name="category_id" required class="w-full border p-2 rounded"></select>
            <input name="movie_title" required class="w-full border p-2 rounded" placeholder="Movie title" />
            <textarea name="description" class="w-full border p-2 rounded" placeholder="Description"></textarea>
            <input type="file" name="file" />
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                <button type="button" id="btn-cancel" class="ml-2">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    async function loadCategories() {
        const res = await axios.get('/api/categories');
        const ul = document.getElementById('categories-list');
        ul.innerHTML = '';
        res.data.forEach(c => {
            const li = document.createElement('li');
            li.innerText = c.name;
            ul.appendChild(li);
        });
        // populate select
        const sel = document.querySelector('#ticket-form select[name=category_id]');
        sel.innerHTML = '<option value="" disabled selected>Select an item in the list</option>';
        res.data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.innerText = c.name;
            sel.appendChild(opt);
        });
    }

    async function loadTickets() {
        const res = await axios.get('/api/tickets');
        const div = document.getElementById('tickets-list');
        div.innerHTML = '';
        res.data.data.forEach(t => {
            const el = document.createElement('div');
            el.className = 'p-3 border-b flex justify-between items-center';
            el.innerHTML = `<div><div class="font-semibold">${t.movie_title}</div><div class="text-sm text-gray-600">${t.description || ''}</div></div>
                            <div class="space-x-2">
                                <button data-slug="${t.slug}" class="btn-edit bg-yellow-400 px-2 py-1 rounded">Edit</button>
                                <button data-slug="${t.slug}" class="btn-delete bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </div>`;
            div.appendChild(el);
        });
    }

    const modal = document.getElementById('modal');
    function openModal(mode, ticket = null){
        const form = document.getElementById('ticket-form');
        form.reset();
        form.dataset.action = mode === 'create' ? 'create' : 'update';
        if (mode === 'update' && ticket) {
            form.movie_title.value = ticket.movie_title || '';
            form.description.value = ticket.description || '';
            form.dataset.slug = ticket.slug;
        } else {
            delete form.dataset.slug;
        }
        document.getElementById('modal-title').innerText = mode === 'create' ? 'New Ticket' : 'Edit Ticket';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // focus
        setTimeout(()=> {
            const input = form.querySelector('input[name=movie_title]');
            if (input) input.focus();
        }, 80);
    }

    function closeModal(){
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('btn-new').addEventListener('click', function(){
        openModal('create');
    });

    document.getElementById('btn-cancel').addEventListener('click', function(){
        closeModal();
    });

    // click backdrop to close
    modal.addEventListener('click', function(e){
        if (e.target === modal) closeModal();
    });

    document.getElementById('ticket-form').addEventListener('submit', async function(e){
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type=submit]');
        const old = btn.innerText;
        btn.disabled = true;
        btn.innerText = 'Saving...';
        const fd = new FormData(form);
        const action = form.dataset.action;
        try {
            if (action === 'create') {
                await axios.post('/api/tickets', fd, { headers: {'Content-Type': 'multipart/form-data'} });
                showToast('Ticket created');
            } else {
                const slug = form.dataset.slug;
                await axios.put('/api/tickets/' + slug, fd, { headers: {'Content-Type': 'multipart/form-data'} });
                showToast('Ticket updated');
            }
            closeModal();
            await loadTickets();
        } catch (err) {
            showToast('Failed to save: ' + (err.response?.data?.message || ''), 'error');
        } finally {
            btn.disabled = false;
            btn.innerText = old;
        }
    });

    document.getElementById('tickets-list').addEventListener('click', async function(e){
        if (e.target.classList.contains('btn-delete')) {
            const slug = e.target.dataset.slug;
            if (!confirm('Delete?')) return;
            try {
                await axios.delete('/api/tickets/' + slug);
                showToast('Ticket deleted');
                await loadTickets();
            } catch (err) {
                showToast('Delete failed', 'error');
            }
        }
        if (e.target.classList.contains('btn-edit')) {
            const slug = e.target.dataset.slug;
            const res = await axios.get('/api/tickets/' + slug);
            const t = res.data;
            openModal('update', t);
        }
    });

    // init
    (async function(){
        loadCategories();
        await loadTickets();
    })();
</script>
@endsection
