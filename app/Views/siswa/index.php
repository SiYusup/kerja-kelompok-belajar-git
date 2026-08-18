<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <!-- Button kembali ke home / menu absensi -->
            <a class="navbar-item" href="/">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Beranda</span>
            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <!-- Judul halaman -->
            <div class="level">
                <div>
                    <h1 class="title">Daftar Siswa</h1>
                    <h2 class="subtitle">Kelola data siswa</h2>
                </div>
                <div>
                    <a class="button is-link is-medium" href="/siswa/tambah">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Tambah Siswa</span>
                    </a>
                </div>
            </div>

            <!-- ================= PENCARIAN ================= -->
            <form action="/siswa" method="GET">
                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left">
                        <input class="input is-rounded is-medium" type="text" name="search"
                               value="<?= htmlspecialchars($keyword) ?>"
                               placeholder="Cari nama, NISN, jurusan, atau kelas...">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <div class="control">
                        <button class="button is-link is-medium is-rounded" type="submit">Cari</button>
                    </div>
                </div>
            </form>

            <p class="has-text-grey mb-4">
                Menampilkan <strong><?= count($siswa) ?></strong> dari <strong><?= $totalData ?></strong> data siswa.
            </p>

            <!-- ================= GRID CARD SISWA ================= -->
            <div class="columns is-multiline is-3">

                <?php if (!empty($siswa)): ?>
                    <?php foreach ($siswa as $row): ?>
                        <div class="column is-one-third">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <div class="is-flex is-align-items-center is-justify-content-center is-rounded has-background-link-light has-text-link" style="width: 48px; height: 48px;">
                                                    <span class="icon is-medium"><i class="fas fa-user"></i></span>
                                                </div>
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-5 mb-0"><?= htmlspecialchars($row['nama']) ?></p>
                                            <p class="subtitle is-6 has-text-grey">NISN: <?= htmlspecialchars($row['nisn']) ?></p>
                                        </div>
                                    </div>

                                    <div class="content">
                                        <p class="mb-2">
                                            <span class="tag is-link is-light"><?= htmlspecialchars($row['nama_kelas']) ?></span>
                                            <span class="tag is-light"><?= $row['jenis_kelamin'] === 'LAKI' ? 'Laki-laki' : 'Perempuan' ?></span>
                                        </p>
                                        <p class="has-text-grey is-size-7 mb-0">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($row['alamat']) ?>
                                        </p>
                                    </div>
                                </div>

                                <footer class="card-footer">
                                    <a class="card-footer-item has-text-link" href="/siswa/detail/<?= $row['id'] ?>" title="Lihat detail">
                                        <span class="icon mr-1"><i class="fas fa-eye"></i></span>Detail
                                    </a>
                                    <a class="card-footer-item has-text-link" href="/siswa/edit/<?= $row['id'] ?>" title="Edit">
                                        <span class="icon mr-1"><i class="fas fa-pencil-alt"></i></span>Update
                                    </a>
                                    <a class="card-footer-item has-text-link" href="/siswa/delete/<?= $row['id'] ?>"
                                       data-confirm="Data siswa ini akan dihapus secara permanen." title="Hapus">
                                        <span class="icon mr-1"><i class="fas fa-trash"></i></span>Delete
                                    </a>
                                </footer>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="column is-full">
                        <div class="notification is-light">
                            Tidak ada data siswa yang cocok dengan pencarian "<?= htmlspecialchars($keyword) ?>".
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- ================= PAGINATION ================= -->
            <?php if ($totalHalaman > 1): ?>
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                    <?php if ($halamanAktif > 1): ?>
                        <a class="pagination-previous" href="?page=<?= $halamanAktif - 1 ?>&search=<?= urlencode($keyword) ?>">Sebelumnya</a>
                    <?php else: ?>
                        <a class="pagination-previous" disabled>Sebelumnya</a>
                    <?php endif; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a class="pagination-next" href="?page=<?= $halamanAktif + 1 ?>&search=<?= urlencode($keyword) ?>">Berikutnya</a>
                    <?php else: ?>
                        <a class="pagination-next" disabled>Berikutnya</a>
                    <?php endif; ?>

                    <ul class="pagination-list">
                        <?php for ($p = 1; $p <= $totalHalaman; $p++): ?>
                            <li>
                                <a class="pagination-link <?= $p === $halamanAktif ? 'is-current' : '' ?>"
                                   href="?page=<?= $p ?>&search=<?= urlencode($keyword) ?>"><?= $p ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </section>

</body>
