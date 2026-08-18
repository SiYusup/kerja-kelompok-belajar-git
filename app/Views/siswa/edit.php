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

            <h1 class="title">Edit Siswa</h1>
            <h2 class="subtitle">Perbarui informasi data siswa</h2>

            <?php if (!empty($siswa)): ?>

                <div class="card">
                    <header class="card-header has-background-link-light">
                        <p class="card-header-title">Form Edit Siswa</p>
                    </header>
                    <div class="card-content">
                        <form action="/siswa/update/<?= $siswa['id'] ?>" method="POST">
                            <div class="columns is-multiline">

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Nama Lengkap</label>
                                        <div class="control">
                                            <input class="input" type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">NISN</label>
                                        <div class="control">
                                            <input class="input" type="text" name="nisn" value="<?= htmlspecialchars($siswa['nisn']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Kelas</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="id_kelas" required>
                                                    <option value="">-- Pilih Kelas --</option>
                                                    <?php foreach ($kelas as $row): ?>
                                                        <option value="<?= $row['id'] ?>"
                                                            <?= (int) $row['id'] === (int) $siswa['id_kelas'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($row['kelas']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Tingkat Kelas</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="kelas" required>
                                                    <option value="">-- Pilih Tingkat --</option>
                                                    <option value="X" <?= $siswa['kelas'] === 'X' ? 'selected' : '' ?>>X</option>
                                                    <option value="XI" <?= $siswa['kelas'] === 'XI' ? 'selected' : '' ?>>XI</option>
                                                    <option value="XII" <?= $siswa['kelas'] === 'XII' ? 'selected' : '' ?>>XII</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Jurusan</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="jurusan" required>
                                                    <option value="">-- Pilih Jurusan --</option>
                                                    <?php foreach (["RPL", "TKJ", "MM", "TBSM", "OTKP", "BDP"] as $j): ?>
                                                        <option value="<?= $j ?>" <?= $siswa['jurusan'] === $j ? 'selected' : '' ?>><?= $j ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Jenis Kelamin</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="jenis_kelamin" required>
                                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                                    <option value="LAKI" <?= $siswa['jenis_kelamin'] === 'LAKI' ? 'selected' : '' ?>>Laki-laki</option>
                                                    <option value="PEREMPUAN" <?= $siswa['jenis_kelamin'] === 'PEREMPUAN' ? 'selected' : '' ?>>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Tempat Lahir</label>
                                        <div class="control">
                                            <input class="input" type="text" name="tempat_lahir" value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Tanggal Lahir</label>
                                        <div class="control">
                                            <input class="input" type="date" name="tanggal_lahir" value="<?= htmlspecialchars($siswa['tanggal_lahir']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-full">
                                    <div class="field">
                                        <label class="label">Alamat</label>
                                        <div class="control">
                                            <textarea class="textarea" name="alamat" required><?= htmlspecialchars($siswa['alamat']) ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-full">
                                    <div class="field is-grouped">
                                        <div class="control">
                                            <button class="button is-link" type="submit">
                                                <span class="icon"><i class="fas fa-save"></i></span>
                                                <span>Simpan Perubahan</span>
                                            </button>
                                        </div>
                                        <div class="control">
                                            <a class="button is-light" href="/siswa">Batal</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                <div class="notification is-light">
                    Data siswa tidak ditemukan.
                </div>
            <?php endif; ?>

        </div>
    </section>

</body>
