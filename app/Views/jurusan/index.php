<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Beranda</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <!-- Judul halaman -->
            <div class="level">
                <div>
                    <h1 class="title">Daftar Jurusan</h1>
                    <h2 class="subtitle">Kelola data jurusan</h2>
                </div>
                <div>
                    <a class="button is-link is-medium" href="/jurusan/tambah">
                        <span class="icon"><i class="fas fa-plus"></i></span>
                        <span>Tambah Jurusan</span>
                    </a>
                </div>
            </div>

            <!-- ================= PENCARIAN ================= -->
            <form action="/jurusan" method="GET" class="mb-4">
                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left">
                        <input class="input is-rounded is-medium" type="text" name="search"
                               value="<?= htmlspecialchars($keyword ?? '') ?>"
                               placeholder="Cari nama jurusan...">
                        <span class="icon is-left"><i class="fas fa-search"></i></span>
                    </div>
                    <div class="control">
                        <button class="button is-link is-medium is-rounded" type="submit">Cari</button>
                    </div>
                    <?php if (!empty($keyword)): ?>
                        <div class="control">
                            <a class="button is-light is-medium is-rounded" href="/jurusan">Reset</a>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

            <!-- ================= LIST JURUSAN ================= -->
            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Nama Jurusan</th>
                            <th class="has-text-centered" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($jurusan)): ?>
                            <?php $no = (($halamanAktif ?? 1) - 1) * ($perHalaman ?? 10) + 1; foreach ($jurusan as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <span class="icon has-text-link mr-1">
                                            <i class="fas fa-graduation-cap"></i>
                                        </span>
                                        <?= htmlspecialchars($row['jurusan']) ?>
                                    </td>
                                    <td class="has-text-centered">
                                        <a class="button is-link is-small" href="/jurusan/edit/<?= $row['id'] ?>" title="Update">
                                            <span class="icon is-small"><i class="fas fa-pencil-alt"></i></span>
                                        </a>
                                        <a class="button is-link is-light is-small" href="/jurusan/delete/<?= $row['id'] ?>"
                                           data-confirm="Data jurusan ini akan dihapus secara permanen." title="Hapus">
                                            <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="has-text-centered has-text-grey">
                                    <?= !empty($keyword) ? 'Data jurusan "' . htmlspecialchars($keyword) . '" tidak ditemukan.' : 'Belum ada data jurusan.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ================= INFO & PAGINATION ================= -->
            <?php if (!empty($jurusan)): ?>
                <p class="has-text-grey">Menampilkan <?= count($jurusan) ?> dari <?= $totalData ?? 0 ?> data jurusan.</p>
            <?php endif; ?>

            <?php if (($totalHalaman ?? 1) > 1): ?>
                <?php $urlHalaman = fn($h) => '/jurusan?search=' . urlencode($keyword ?? '') . '&page=' . $h; ?>
                <nav class="pagination is-centered mt-4" role="navigation" aria-label="pagination">
                    <a class="pagination-previous <?= ($halamanAktif ?? 1) <= 1 ? 'is-disabled' : '' ?>"
                       href="<?= ($halamanAktif ?? 1) > 1 ? $urlHalaman(($halamanAktif ?? 1) - 1) : '#' ?>">Sebelumnya</a>
                    <a class="pagination-next <?= ($halamanAktif ?? 1) >= $totalHalaman ? 'is-disabled' : '' ?>"
                       href="<?= ($halamanAktif ?? 1) < $totalHalaman ? $urlHalaman(($halamanAktif ?? 1) + 1) : '#' ?>">Berikutnya</a>
                    <ul class="pagination-list">
                        <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                            <li>
                                <a class="pagination-link <?= $i === ($halamanAktif ?? 1) ? 'is-current' : '' ?>"
                                   href="<?= $urlHalaman($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </section>

</body>
