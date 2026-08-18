<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Absensi</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <h1 class="title">Daftar Mata Pelajaran</h1>
            <h2 class="subtitle">Kelola data mata pelajaran</h2>

            <!-- ================= PENCARIAN ================= -->
            <div class="field">
                <div class="control has-icons-left">
                    <input class="input is-rounded is-medium" type="text"
                           placeholder="Cari mata pelajaran...">
                    <span class="icon is-small is-left">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>

            <!-- ================= TABEL DATA ================= -->
            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mata Pelajaran</th>
                            <th class="has-text-centered">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mapel)): ?>
                            <?php $no = 1; foreach ($mapel as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['mapel']) ?></td>
                                    <td class="has-text-centered">
                                        <a class="button is-link is-small" href="/mapel/edit/<?= $row['id'] ?>" title="Edit">
                                            <span class="icon is-small"><i class="fas fa-pencil-alt"></i></span>
                                        </a>
                                        <a class="button is-link is-light is-small" href="/mapel/delete/<?= $row['id'] ?>" title="Hapus">
                                            <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="has-text-centered has-text-grey">Belum ada data mata pelajaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>

</body>
