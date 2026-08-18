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

            <h1 class="title">Jadwal Absensi</h1>
            <h2 class="subtitle">Buat jadwal absensi berdasarkan kelas, guru, mapel, bulan, dan tahun</h2>

            <!-- ================= FORM BUAT JADWAL ================= -->
            <div class="card mb-5">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Buat Jadwal Absensi Baru</p>
                </header>
                <div class="card-content">
                    <form action="/absensi/create" method="POST">
                        <div class="columns is-multiline">

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Kelas</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="id_kelas" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php foreach ($kelas as $row): ?>
                                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['kelas']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Guru</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="id_guru" required>
                                                <option value="">-- Pilih Guru --</option>
                                                <?php foreach ($guru as $row): ?>
                                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Mapel</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="id_mapel" required>
                                                <option value="">-- Pilih Mapel --</option>
                                                <?php foreach ($mapel as $row): ?>
                                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['mapel']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Bulan</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="bulan" required>
                                                <option value="">-- Pilih Bulan --</option>
                                                <?php foreach ($bulan as $b): ?>
                                                    <option value="<?= $b ?>"><?= $b ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Tahun</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="tahun" required>
                                                <option value="">-- Pilih Tahun --</option>
                                                <?php foreach ($tahun as $t): ?>
                                                    <option value="<?= $t ?>"><?= $t ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">&nbsp;</label>
                                    <div class="control">
                                        <button class="button is-link is-fullwidth" type="submit">
                                            <span class="icon"><i class="fas fa-plus"></i></span>
                                            <span>Buat Jadwal</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= DAFTAR JADWAL (CARD) ================= -->
            <?php if (!empty($jadwal)): ?>
                <div class="columns is-multiline is-3">
                    <?php foreach ($jadwal as $row): ?>
                        <div class="column is-one-third">
                            <div class="card">
                                <header class="card-header has-background-link-light">
                                    <p class="card-header-title">
                                        <span class="icon mr-2"><i class="fas fa-chalkboard-teacher"></i></span>
                                        <?= htmlspecialchars($row['kelas']) ?>
                                    </p>
                                </header>
                                <div class="card-content">
                                    <div class="content">
                                        <p class="mb-2">
                                            <i class="fas fa-user has-text-link mr-2"></i><strong><?= htmlspecialchars($row['nama_guru']) ?></strong>
                                        </p>
                                        <p class="mb-2">
                                            <i class="fas fa-book has-text-link mr-2"></i><?= htmlspecialchars($row['mapel']) ?>
                                        </p>
                                        <p class="mb-0">
                                            <span class="tag is-link is-light"><?= htmlspecialchars($row['bulan']) ?></span>
                                            <span class="tag is-light"><?= htmlspecialchars($row['tahun']) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <footer class="card-footer">
                                    <a class="card-footer-item has-text-link" href="/absensi/detail/<?= $row['id'] ?>">
                                        <span class="icon mr-1"><i class="fas fa-folder-open"></i></span>Open
                                    </a>
                                    <a class="card-footer-item has-text-link" href="/absensi/edit/<?= $row['id'] ?>">
                                        <span class="icon mr-1"><i class="fas fa-pencil-alt"></i></span>Update
                                    </a>
                                    <a class="card-footer-item has-text-link" href="/absensi/rekap/<?= $row['id'] ?>">
                                        <span class="icon mr-1"><i class="fas fa-chart-bar"></i></span>Rekap
                                    </a>
                                    <a class="card-footer-item has-text-link" href="/absensi/delete/<?= $row['id'] ?>"
                                       data-confirm="Jadwal beserta seluruh data absensinya akan dihapus.">
                                        <span class="icon mr-1"><i class="fas fa-trash"></i></span>Delete
                                    </a>
                                </footer>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="notification is-light">
                    Belum ada jadwal absensi. Silakan buat jadwal terlebih dahulu melalui form di atas.
                </div>
            <?php endif; ?>

        </div>
    </section>

</body>
