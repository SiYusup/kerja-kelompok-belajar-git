<body>

    <!-- ================= HERO / UCAPAN SELAMAT ================= -->
    <?php
        $jam = (int) date("H");
        if ($jam >= 5 && $jam < 12) {
            $sapaan = "Selamat Pagi";
        } elseif ($jam < 15) {
            $sapaan = "Selamat Siang";
        } elseif ($jam < 18) {
            $sapaan = "Selamat Sore";
        } else {
            $sapaan = "Selamat Malam";
        }
    ?>

    <section class="hero is-link">
        <div class="hero-body">
            <div class="container has-text-centered">
                <p class="title">Selamat Datang </p>
                <p class="subtitle pb-3">Aplikasi Sistem Absensi Guru</p>
                <p class="subtitle">Kelola data siswa, guru, dan rekap kehadiran dengan mudah dan terstruktur.</p>
            </div>
        </div>
    </section>

    <!-- ================= KARTU FITUR ================= -->
    <section class="section">
        <div class="container">

            <h2 class="title is-4 has-text-centered">Pilih Menu</h2>

            <div class="columns is-multiline is-centered">

                <!-- ===== Cek Siswa ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-user-graduate"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Cek Siswa</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Lihat dan kelola data siswa beserta kelas dan NISN-nya.
                            </p>
                            <a class="button is-link" href="/siswa">Buka</a>
                        </div>
                    </div>
                </div>

                <!-- ===== Cek Guru ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Cek Guru</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Lihat dan kelola data guru beserta mapel yang diampu.
                            </p>
                            <a class="button is-link" href="/guru">Buka</a>
                        </div>
                    </div>
                </div>

                <!-- ===== Absensi Siswa ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Absensi Siswa</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Buat jadwal dan rekap kehadiran siswa per kelas, guru, dan mapel.
                            </p>
                            <a class="button is-link" href="/absensi">Buka</a>
                        </div>
                    </div>
                </div>

                <!-- ===== Cek Jurusan ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Cek Jurusan</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Lihat dan kelola daftar jurusan yang tersedia.
                            </p>
                            <a class="button is-link" href="/jurusan">Buka</a>
                        </div>
                    </div>
                </div>

                
                <!-- ===== Cek Kelas ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-school"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Cek Kelas</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Lihat dan kelola daftar kelas yang tersedia.
                            </p>
                            <a class="button is-link" href="/kelas">Buka</a>
                        </div>
                    </div>
                </div>

                <!-- ===== Cek Mata Pelajaran ===== -->
                <div class="column is-one-third">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2.5rem;">
                                <i class="fas fa-book"></i>
                            </span>
                            <p class="title is-5 mt-3 mb-2">Cek Mata Pelajaran</p>
                            <p class="subtitle is-6 has-text-grey mb-4">
                                Lihat dan kelola daftar mata pelajaran yang tersedia.
                            </p>
                            <a class="button is-link" href="/mapel">Buka</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

</body>
