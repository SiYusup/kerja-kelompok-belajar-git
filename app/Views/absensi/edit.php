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

            <h1 class="title">Edit Jadwal Absensi</h1>
            <h2 class="subtitle">Perbarui informasi kelas, guru, mapel, bulan, dan tahun</h2>

            <div class="card">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Form Edit Jadwal</p>
                </header>
                <div class="card-content">
                    <form action="/absensi/update/<?= $jadwal['id'] ?>" method="POST">
                        <div class="columns is-multiline">

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">Kelas</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select name="id_kelas" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php foreach ($kelas as $row): ?>
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= (int) $row['id'] === (int) $jadwal['id_kelas'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($row['kelas']) ?>
                                                    </option>
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
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= (int) $row['id'] === (int) $jadwal['id_guru'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($row['nama']) ?>
                                                    </option>
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
                                                    <option value="<?= $row['id'] ?>"
                                                        <?= (int) $row['id'] === (int) $jadwal['id_mapel'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($row['mapel']) ?>
                                                    </option>
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
                                                    <option value="<?= $b ?>" <?= $b === $jadwal['bulan'] ? 'selected' : '' ?>><?= $b ?></option>
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
                                                    <option value="<?= $t ?>" <?= $t === $jadwal['tahun'] ? 'selected' : '' ?>><?= $t ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-one-quarter">
                                <div class="field">
                                    <label class="label">&nbsp;</label>
                                    <div class="control is-flex">
                                        <button class="button is-link is-fullwidth" type="submit">
                                            <span class="icon"><i class="fas fa-save"></i></span>
                                            <span>Simpan Perubahan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

</body>
