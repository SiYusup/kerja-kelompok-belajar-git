<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/guru">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Daftar Guru</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <h1 class="title">Detail Guru</h1>
            <h2 class="subtitle">Informasi lengkap data guru</h2>

            <?php if (!empty($guru)): ?>

                <div class="card">
                    <header class="card-header has-background-link-light">
                        <p class="card-header-title">
                            <span class="icon mr-2"><i class="fas fa-user-tie"></i></span>
                            <?= htmlspecialchars($guru['nama']) ?>
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="columns is-multiline">

                            <div class="column is-half">
                                <p class="heading">NIP</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($guru['nip']) ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Mapel</p>
                                <p class="subtitle is-6 mb-0">
                                    <span class="tag is-link is-light"><?= htmlspecialchars($guru['nama_mapel']) ?></span>
                                </p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Jurusan</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($guru['jurusan']) ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Jenis Kelamin</p>
                                <p class="subtitle is-6 mb-0"><?= $guru['jenis_kelamin'] === 'LAKI' ? 'Laki-laki' : 'Perempuan' ?></p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Tempat, Tanggal Lahir</p>
                                <p class="subtitle is-6 mb-0">
                                    <?= htmlspecialchars($guru['tempat_lahir']) ?>, <?= date('d M Y', strtotime($guru['tanggal_lahir'])) ?>
                                </p>
                            </div>
                            <div class="column is-half">
                                <p class="heading">Alamat</p>
                                <p class="subtitle is-6 mb-0"><?= htmlspecialchars($guru['alamat']) ?></p>
                            </div>

                        </div>
                    </div>
                    <footer class="card-footer">
                        <a class="card-footer-item has-text-link" href="/guru">Kembali</a>
                        <a class="card-footer-item has-text-link" href="/guru/edit/<?= $guru['id'] ?>">
                            <span class="icon mr-1"><i class="fas fa-pencil-alt"></i></span>Update
                        </a>
                    </footer>
                </div>

            <?php else: ?>
                <div class="notification is-light">
                    Data guru tidak ditemukan.
                </div>
            <?php endif; ?>

        </div>
    </section>

</body>
