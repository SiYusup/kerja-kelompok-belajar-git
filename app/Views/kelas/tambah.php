<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/kelas">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Daftar Kelas</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <h1 class="title">Tambah Kelas</h1>
            <h2 class="subtitle">Isi form berikut untuk menambahkan data kelas baru</h2>

            <div class="card">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Form Tambah Kelas</p>
                </header>
                <div class="card-content">
                    <form action="/kelas/create" method="POST">
                        <div class="field">
                            <label class="label">Nama Kelas</label>
                            <div class="control">
                                <input class="input" type="text" name="kelas" placeholder="Contoh: XII RPL" required>
                            </div>
                        </div>

                        <div class="field is-grouped">
                            <div class="control">
                                <button class="button is-link" type="submit">
                                    <span class="icon"><i class="fas fa-save"></i></span>
                                    <span>Simpan</span>
                                </button>
                            </div>
                            <div class="control">
                                <a class="button is-light" href="/kelas">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

</body>
