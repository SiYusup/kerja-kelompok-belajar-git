<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/siswa">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Daftar Siswa</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <h1 class="title">Detail Siswa</h1>
            <h2 class="subtitle">Informasi lengkap data siswa</h2>

            <?php if (!empty($siswa)): ?>

                <div class="card">
                    <header class="card-header has-background-link-light">
                        <p class="card-header-title">
                            <span class="icon mr-2"><i class="fas fa-user-graduate"></i></span>
                            <?= htmlspecialchars($siswa['nama']) ?>
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="columns is-multiline">

                            <div class="column is-half">
                                <p class="heading">NISN</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($siswa['nisn']) ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Kelas</p>
                                <p class="subtitle is-6 mb-0">
                                    <span class="tag is-link is-light"><?= htmlspecialchars($siswa['nama_kelas']) ?></span>
                                </p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Jurusan</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($siswa['jurusan']) ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Jenis Kelamin</p>
                                <p class="subtitle is-6 mb-0"><?= $siswa['jenis_kelamin'] === 'LAKI' ? 'Laki-laki' : 'Perempuan' ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Tempat, Tanggal Lahir</p>
                                <p class="subtitle is-6 mb-0">
                                    <?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= date('d M Y', strtotime($siswa['tanggal_lahir'])) ?>
                                </p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Alamat</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($siswa['alamat']) ?></p>
                            </div>

                        </div>
                    </div>
                    <footer class="card-footer">
                        <a class="card-footer-item has-text-link" href="/siswa">Kembali</a>
                        <a class="card-footer-item has-text-link" href="/siswa/edit/<?= $siswa['id'] ?>">
                            <span class="icon mr-1"><i class="fas fa-pencil-alt"></i></span>Update
                        </a>
                    </footer>
                </div>

            <?php else: ?>
                <div class="notification is-light">
                    Data siswa tidak ditemukan.
                </div>
            <?php endif; ?>

        </div>
    </section>

</body>
