<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar is-link" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/absensi">
                <span class="icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="has-text-weight-semibold">Kembali ke Jadwal Absensi</span>
            </a>
        </div>
    </nav>

    <!-- ================= KONTEN UTAMA ================= -->
    <section class="section">
        <div class="container">

            <h1 class="title">Detail Absensi</h1>
            <h2 class="subtitle">Rekap kehadiran siswa untuk jadwal berikut</h2>

            <!-- ================= INFORMASI JADWAL ================= -->
            <div class="card mb-5">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Informasi Jadwal</p>
                </header>
                <div class="card-content">
                    <div class="columns is-multiline">
                        <div class="column is-one-quarter">
                            <p class="heading">Kelas</p>
                            <p class="subtitle is-6 mb-0"><strong><?= htmlspecialchars($jadwal['kelas']) ?></strong></p>
                        </div>
                        <div class="column is-one-quarter">
                            <p class="heading">Guru</p>
                            <p class="subtitle is-6 mb-0"><strong><?= htmlspecialchars($jadwal['nama_guru']) ?></strong></p>
                        </div>
                        <div class="column is-one-quarter">
                            <p class="heading">Mapel</p>
                            <p class="subtitle is-6 mb-0"><strong><?= htmlspecialchars($jadwal['mapel']) ?></strong></p>
                        </div>
                        <div class="column is-one-quarter">
                            <p class="heading">Bulan / Tahun</p>
                            <p class="mb-0">
                                <span class="tag is-link is-light"><?= htmlspecialchars($jadwal['bulan']) ?></span>
                                <span class="tag is-light"><?= htmlspecialchars($jadwal['tahun']) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= FORM TAMBAH ABSENSI ================= -->
            <div class="card mb-5">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Tambah Absensi Siswa</p>
                </header>
                <div class="card-content">
                    <form action="/absensi/tambahAbsensi/<?= $jadwal['id'] ?>" method="POST">
                        <div class="columns is-multiline">

                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label">Siswa</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="id_siswa" required>
                                                <option value="">-- Pilih Siswa --</option>
                                                <?php foreach ($siswa as $row): ?>
                                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?> (NISN: <?= htmlspecialchars($row['nisn']) ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label">Tanggal</label>
                                    <div class="control">
                                        <input class="input" type="datetime-local" name="tanggal" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label">Kehadiran</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="kehadiran" required>
                                                <option value="">-- Pilih Kehadiran --</option>
                                                <?php foreach ($kehadiran as $k): ?>
                                                    <option value="<?= $k ?>"><?= $k ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-third">
                                <div class="field">
                                    <label class="label">&nbsp;</label>
                                    <div class="control">
                                        <button class="button is-link is-fullwidth" type="submit">
                                            <span class="icon"><i class="fas fa-plus"></i></span>
                                            <span>Tambah Absensi</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= TABEL ABSENSI ================= -->
            <div class="table-container">
                <table class="table is-fullwidth is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Tanggal</th>
                            <th>Kehadiran</th>
                            <th class="has-text-centered">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($absensi)): ?>
                            <?php $no = 1; foreach ($absensi as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                    <td><?= htmlspecialchars($row['nisn_siswa']) ?></td>
                                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                    <td>
                                        <span class="tag is-link is-light"><?= htmlspecialchars($row['kehadiran']) ?></span>
                                    </td>
                                    <td class="has-text-centered">
                                        <a class="button is-link is-light is-small" href="/absensi/hapusAbsensi/<?= $jadwal['id'] ?>/<?= $row['id'] ?>"
                                           data-confirm="Data kehadiran siswa ini akan dihapus." title="Hapus">
                                            <span class="icon is-small"><i class="fas fa-trash"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="has-text-centered has-text-grey">Belum ada data absensi untuk jadwal ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>

</body>
