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

            <h1 class="title">Edit Guru</h1>
            <h2 class="subtitle">Perbarui informasi data guru</h2>

            <?php if (!empty($guru)): ?>

                <div class="card">
                    <header class="card-header has-background-link-light">
                        <p class="card-header-title">Form Edit Guru</p>
                    </header>
                    <div class="card-content">
                        <form action="/guru/update/<?= $guru['id'] ?>" method="POST">
                            <div class="columns is-multiline">

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Nama Lengkap</label>
                                        <div class="control">
                                            <input class="input" type="text" name="nama" value="<?= htmlspecialchars($guru['nama']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">NIP</label>
                                        <div class="control">
                                            <input class="input" type="text" name="nip" value="<?= htmlspecialchars($guru['nip']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Mata Pelajaran</label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select name="id_mapel" required>
                                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                                    <?php foreach ($mapel as $row): ?>
                                                        <option value="<?= $row['id'] ?>"
                                                            <?= (int) $row['id'] === (int) $guru['id_mapel'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($row['mapel']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
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
                                                        <option value="<?= $j ?>" <?= $guru['jurusan'] === $j ? 'selected' : '' ?>><?= $j ?></option>
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
                                                    <option value="LAKI" <?= $guru['jenis_kelamin'] === 'LAKI' ? 'selected' : '' ?>>Laki-laki</option>
                                                    <option value="PEREMPUAN" <?= $guru['jenis_kelamin'] === 'PEREMPUAN' ? 'selected' : '' ?>>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Tempat Lahir</label>
                                        <div class="control">
                                            <input class="input" type="text" name="tempat_lahir" value="<?= htmlspecialchars($guru['tempat_lahir']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-half">
                                    <div class="field">
                                        <label class="label">Tanggal Lahir</label>
                                        <div class="control">
                                            <input class="input" type="date" name="tanggal_lahir" value="<?= htmlspecialchars($guru['tanggal_lahir']) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-full">
                                    <div class="field">
                                        <label class="label">Alamat</label>
                                        <div class="control">
                                            <textarea class="textarea" name="alamat" required><?= htmlspecialchars($guru['alamat']) ?></textarea>
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
                                            <a class="button is-light" href="/guru">Batal</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                <div class="notification is-light">
                    Data guru tidak ditemukan.
                </div>
            <?php endif; ?>

        </div>
    </section>

</body>
