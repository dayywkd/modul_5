<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cinebox</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            tealbrand: '#09A79A',
            lightteal: '#E7F8F6',
            softblue: '#E8FBFF'
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui']
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: Inter, sans-serif; }
    .soft-shadow { box-shadow: 0 6px 18px rgba(9,167,154,0.08); }
    .corner-num { position: absolute; top: 8px; right: 12px; background: rgba(255,255,255,0.85); width:28px; height:28px; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-weight:600; color:#374151; font-size:0.85rem; }
  </style>
</head>

<body class="bg-gradient-to-b from-white to-softblue min-h-screen text-gray-800">

  <!-- HEADER -->
  <header class="flex items-center justify-between px-6 py-3 bg-white shadow-sm">
    <div class="flex items-center space-x-4">
      <img src="logo.png" alt="Cinebox Logo" class="h-10 w-auto">
      <div class="hidden sm:flex bg-gray-100 text-gray-800 px-4 py-1 rounded-full items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 11c0 .552-.224 1.05-.586 1.414A1.993 1.993 0 0110 13a1.993 1.993 0 01-1.414-.586A1.993 1.993 0 018 11c0-.552.224-1.05.586-1.414A1.993 1.993 0 0110 9c.552 0 1.05.224 1.414.586.362.364.586.862.586 1.414z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 22s8-4.5 8-11a8 8 0 10-16 0c0 6.5 8 11 8 11z" />
        </svg>
        <span class="font-medium">Malang</span>
      </div>
    </div>

    <nav id="nav-menu" class="hidden md:flex items-center space-x-6">
      <div id="guest-nav" class="flex items-center space-x-6">
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-black">Login</a>
        <a href="{{ route('register') }}" class="bg-tealbrand text-white px-5 py-2 rounded-full font-semibold hover:bg-teal-700">Buat akun</a>
      </div>
      <div id="auth-nav" class="hidden flex items-center space-x-6">
        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-black">Dashboard</a>
        <button id="btn-logout" class="text-red-500 hover:text-red-700">Logout</button>
      </div>
    </nav>
  </header>

  <!-- HERO -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
    <div class="bg-gradient-to-r from-white to-lightteal rounded-2xl p-6 sm:p-12 soft-shadow">
      <div class="text-center">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-800 mb-6">Feel the movies beyond</h1>

        <!-- Search -->
        <div class="max-w-2xl mx-auto">
          <div class="relative">
            <input id="search" type="search" placeholder="Cari film atau bioskop"
              class="w-full rounded-full py-3 sm:py-4 pl-5 pr-12 text-gray-700 placeholder-gray-400 soft-shadow focus:outline-none">
            <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-white rounded-full p-2 soft-shadow">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            </button>
          </div>
        </div>

        <!-- Categories -->
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-6 justify-items-center">
          <div class="flex flex-col items-center gap-2">
            <div class="rounded-lg p-4 bg-white soft-shadow">
              <img src="theater.png" class="h-8 w-8" alt="Bioskop">
            </div>
            <span class="text-sm text-gray-600">Bioskop</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <div class="rounded-lg p-4 bg-white soft-shadow">
              <img src="clapperboard.png" class="h-8 w-8" alt="Film">
            </div>
            <span class="text-sm text-gray-600">Film</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <div class="rounded-lg p-4 bg-white soft-shadow">
              <img src="popcorn.png" class="h-8 w-8" alt="Popcorn">
            </div>
            <span class="text-sm text-gray-600">C. Food</span>
          </div>
          <div class="flex flex-col items-center gap-2">
            <div class="rounded-lg p-4 bg-white soft-shadow">
              <img src="sofa.png" class="h-7 w-7" alt="Sewa Tempat">
            </div>
            <span class="text-sm text-gray-600">Sewa Tempat</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FILM SECTION -->
  <section class="mt-10 max-w-6xl mx-auto px-4 sm:px-6 md:px-8 pb-12">
    <div class="flex flex-col sm:flex-row sm:items-center items-start justify-between mb-6 gap-4">
      <h2 class="text-lg sm:text-xl md:text-2xl font-semibold">Lagi Tayang</h2>
      <a href="#" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 border border-tealbrand text-tealbrand rounded-full text-xs sm:text-sm whitespace-nowrap">
        Lihat semua ›
      </a>
    </div>

    <!-- Grid Film Dinamis -->
    <div id="movie-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6 text-center">
      <p class="col-span-full text-gray-500">Memuat data film...</p>
    </div>
  </section>

  <!-- SERVICES -->
<section id="services" class="bg-lightteal py-12">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <h2 class="text-2xl font-bold text-center mb-10">Layanan Kami</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
      <div class="bg-white rounded-xl p-6 soft-shadow text-center">
        <img src="ticket.png" class="h-12 mx-auto mb-4" alt="Pesan Tiket">
        <h3 class="font-semibold"> <a href="#" class="hover:text-tealbrand">Pesan Tiket</a></h3>
        <p class="text-sm text-gray-600 mt-2">Pemesanan tiket cepat & aman langsung dari smartphone Anda.</p>
      </div>
      <div class="bg-white rounded-xl p-6 soft-shadow text-center">
        <img src="sofa.png" class="h-12 mx-auto mb-4" alt="Pilih Kursi">
        <h3 class="font-semibold">Pilih Kursi</h3>
        <p class="text-sm text-gray-600 mt-2">Pilih kursi favorit dengan layout interaktif.</p>
      </div>
      <div class="bg-white rounded-xl p-6 soft-shadow text-center">
        <img src="promo.png" class="h-12 mx-auto mb-4" alt="Promo">
        <h3 class="font-semibold">Promo Spesial</h3>
        <p class="text-sm text-gray-600 mt-2">Nikmati diskon eksklusif dan voucher menarik setiap minggu.</p>
      </div>
      <div class="bg-white rounded-xl p-6 soft-shadow text-center">
        <img src="popcorn.png" class="h-12 mx-auto mb-4" alt="Snack Order">
        <h3 class="font-semibold">Pesan Snack</h3>
        <p class="text-sm text-gray-600 mt-2">Beli popcorn & minuman langsung bersama tiket.</p>
      </div>
    </div>
  </div>
</section>

  <!-- ABOUT -->
  <section id="about" class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h2 class="text-2xl font-bold text-center mb-6">Tentang Cinebox</h2>
    <p class="text-gray-600 text-center max-w-3xl mx-auto">
      Cinebox adalah platform modern untuk memesan tiket bioskop secara online.
      Kami menghadirkan pengalaman menonton yang lebih mudah, cepat, dan aman.
    </p>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <h2 class="text-2xl font-bold text-center mb-6">Kontak Kami</h2>
    <form class="max-w-2xl mx-auto space-y-4">
      <input type="text" placeholder="Nama" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tealbrand">
      <input type="email" placeholder="Email" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tealbrand">
      <textarea rows="4" placeholder="Pesan" class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tealbrand"></textarea>
      <button type="submit" class="bg-tealbrand text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-700">Kirim Pesan</button>
    </form>
  </section>

  <!-- FOOTER -->
  <footer class="mt-12">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <div class="bg-white rounded-2xl p-6 sm:p-8 soft-shadow w-full">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-3">
            <img src="logo.png" alt="Cinebox Logo" class="h-8 w-auto">
          </div>
          <nav class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
            <a href="#" class="hover:text-tealbrand">Tentang</a>
            <a href="#" class="hover:text-tealbrand">Kebijakan Privasi</a>
            <a href="#" class="hover:text-tealbrand">Syarat & Ketentuan</a>
            <a href="#contact" class="hover:text-tealbrand">Kontak</a>
          </nav>
        </div>
        <div class="mt-6 text-center text-xs text-gray-500">
          © <span id="year"></span> Cinebox — Demo. Semua hak cipta.
        </div>
      </div>
    </div>
  </footer>

 <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
 <script>
  document.addEventListener("DOMContentLoaded", () => {
    // Auth logic
    const token = localStorage.getItem('jwt_token');
    const guestNav = document.getElementById('guest-nav');
    const authNav = document.getElementById('auth-nav');
    const btnLogout = document.getElementById('btn-logout');

    if (token) {
      guestNav.classList.add('hidden');
      authNav.classList.remove('hidden');
    } else {
      guestNav.classList.remove('hidden');
      authNav.classList.add('hidden');
    }

    btnLogout.addEventListener('click', async () => {
      try {
        await axios.post('/api/logout', {}, {
          headers: { Authorization: `Bearer ${token}` }
        });
      } catch (e) {}
      localStorage.removeItem('jwt_token');
      window.location.reload();
    });

    const movieGrid = document.getElementById("movie-grid");

    const API_KEY = "0eede7ca6e8583c63fe4f4c7f1a0d215";
    const BASE_URL = "https://api.themoviedb.org/3";
    const IMG_URL = "https://image.tmdb.org/t/p/w500";

    // Fungsi ambil data film (sedang tayang)
    function fetchMovies(url) {
      movieGrid.innerHTML = `<p class="text-gray-500 text-center col-span-full">Memuat...</p>`;

      fetch(url)
        .then(response => {
          if (!response.ok) throw new Error("Gagal memuat data dari TMDb");
          return response.json();
        })
        .then(data => {
          const movies = data.results.slice(0, 12);
          movieGrid.innerHTML = "";

          if (movies.length === 0) {
            movieGrid.innerHTML = `<p class="text-gray-500 text-center col-span-full">Film tidak ditemukan 😢</p>`;
            return;
          }

          movies.forEach((movie, index) => {
            const poster = movie.poster_path
              ? `${IMG_URL}${movie.poster_path}`
              : "https://via.placeholder.com/500x750?text=No+Image";

            const card = document.createElement("div");
            card.className = "relative bg-white rounded-xl overflow-hidden soft-shadow group transform transition-transform duration-300 hover:shadow-lg";

            card.innerHTML = `
              <div class="corner-num">${index + 1}</div>
              <div class="relative">
                <img src="${poster}" alt="Poster ${movie.title}" class="w-full h-52 md:h-64 object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <button class="bg-white text-tealbrand px-3 py-2 rounded-full font-semibold text-sm lihat-btn" data-id="${movie.id}">Lihat</button>
                </div>
              </div>
              <div class="p-3">
                <span class="inline-block bg-tealbrand text-white text-xs px-3 py-1 rounded-full -mt-6 transform translate-y-1">
                  Sedang Tayang
                </span>
                <h3 class="mt-3 font-semibold text-xs sm:text-sm">${movie.title}</h3>
              </div>
            `;

            movieGrid.appendChild(card);
          });

          // Event klik tombol "Lihat"
          document.querySelectorAll(".lihat-btn").forEach(btn => {
            btn.addEventListener("click", () => showDetails(btn.dataset.id));
          });
        })
        .catch(error => {
          movieGrid.innerHTML = `<p class="text-red-500 text-center col-span-full">Gagal memuat data film </p>`;
          console.error("Error fetching movies:", error);
        });
    }

    // Fungsi detail film dengan fallback Bahasa Inggris
    function showDetails(movieId) {
      fetch(`${BASE_URL}/movie/${movieId}?api_key=${API_KEY}&language=id-ID`)
        .then(response => response.json())
        .then(movie => {
          if (!movie.overview) {
            return fetch(`${BASE_URL}/movie/${movieId}?api_key=${API_KEY}&language=en-US`)
              .then(res => res.json())
              .then(engMovie => ({ ...engMovie, fallback: true }));
          }
          return movie;
        })
        .then(movie => {
          const poster = movie.poster_path
            ? `${IMG_URL}${movie.poster_path}`
            : "https://via.placeholder.com/500x750?text=No+Image";

          const modal = document.createElement("div");
          modal.className = "fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4";
          modal.innerHTML = `
            <div class="bg-white rounded-xl max-w-md w-full relative p-4 shadow-lg overflow-y-auto max-h-[90vh]">
              <button id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-black font-bold text-lg">&times;</button>
              <img src="${poster}" alt="${movie.title}" class="rounded-lg w-full mb-3">
              <h2 class="text-lg font-bold mb-2">${movie.title}</h2>
              <p class="text-sm text-gray-700 mb-2">
                ${movie.overview || "Deskripsi tidak tersedia."}
                ${movie.fallback ? "<span class='text-xs text-gray-500'>(Bahasa Inggris)</span>" : ""}
              </p>
              <p class="text-sm text-gray-600"><strong>Rating:</strong> ⭐ ${movie.vote_average.toFixed(1)}</p>
              <p class="text-sm text-gray-600"><strong>Tanggal rilis:</strong> ${movie.release_date}</p>
            </div>
          `;
          document.body.appendChild(modal);

          document.getElementById("closeModal").addEventListener("click", () => modal.remove());
          modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.remove();
          });
        });
    }

    // Tampilkan film sedang tayang saat pertama kali dibuka
    fetchMovies(`${BASE_URL}/movie/now_playing?api_key=${API_KEY}&language=id-ID&page=1`);

    // Update tahun footer
    document.getElementById("year").textContent = new Date().getFullYear();
  });
</script>

</body>
</html>
