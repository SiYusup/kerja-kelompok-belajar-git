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

            <h1 class="title">Tambah Guru</h1>
            <h2 class="subtitle">Isi form berikut untuk menambahkan data guru baru</h2>

            <div class="card">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">Form Tambah Guru</p>
                </header>
                <div class="card-content">
                    <form action="/guru/create" method="POST">
                        <div class="columns is-multiline">

                            <div class="column is-half">
                                <div class="field">
                                    <label class="label">Nama Lengkap</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nama" placeholder="Nama lengkap guru" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-half">
                                <div class="field">
                                    <label class="label">NIP</label>
                                    <div class="control">
                                        <input class="input" type="text" name="nip" placeholder="Nomor Induk Pegawai" required>
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
                                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['mapel']) ?></option>
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
                                                <option value="RPL">RPL</option>
                                                <option value="TKJ">TKJ</option>
                                                <option value="MM">MM</option>
                                                <option value="TBSM">TBSM</option>
                                                <option value="OTKP">OTKP</option>
                                                <option value="BDP">BDP</option>
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
                                                <option value="LAKI">Laki-laki</option>
                                                <option value="PEREMPUAN">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-half">
                                <div class="field">
                                    <label class="label">Tempat Lahir</label>
                                    <div class="control">
                                        <input class="input" type="text" name="tempat_lahir" placeholder="Kota kelahiran" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-half">
                                <div class="field">
                                    <label class="label">Tanggal Lahir</label>
                                    <div class="control">
                                        <input class="input" type="date" name="tanggal_lahir" required>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-full">
                                <div class="field">
                                    <label class="label">Alamat</label>
                                    <div class="control">
                                        <textarea class="textarea" name="alamat" placeholder="Alamat lengkap guru" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-full">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button class="button is-link" type="submit">
                                            <span class="icon"><i class="fas fa-save"></i></span>
                                            <span>Simpan</span>
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

        </div>
    </section>

</body>
