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

            <?php
            $warnaStatus = [
                'HADIR' => 'is-success',
                'IZIN' => 'is-info',
                'SAKIT' => 'is-warning',
                'ALPHA' => 'is-danger',
            ];
            $kodeStatus = [
                'HADIR' => 'M',
                'IZIN' => 'I',
                'SAKIT' => 'S',
                'ALPHA' => 'A',
            ];
            $bulanId = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            $totalKehadiran = array_sum($statistikMap ?? []);
            $hadir = $statistikMap['HADIR'] ?? 0;
            $persentase = $totalKehadiran > 0 ? round($hadir / $totalKehadiran * 100) : 0;
            ?>

            <!-- Judul halaman -->
            <div class="level">
                <div>
                    <h1 class="title">Rekap Absensi</h1>
                    <h2 class="subtitle">Dashboard rekapan kehadiran siswa per jadwal</h2>
                </div>
                <div>
                    <a class="button is-link is-medium" href="/absensi/detail/<?= $jadwal['id'] ?>">
                        <span class="icon"><i class="fas fa-clipboard-check"></i></span>
                        <span>Kelola Absensi</span>
                    </a>
                </div>
            </div>

            <!-- ================= INFORMASI JADWAL ================= -->
            <div class="card mb-5">
                <header class="card-header has-background-link-light">
                    <p class="card-header-title">
                        <span class="icon mr-2"><i class="fas fa-chalkboard-teacher"></i></span>
                        Informasi Jadwal
                    </p>
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
                            <small class="has-text-grey">NIP: <?= htmlspecialchars($jadwal['nip_guru']) ?></small>
                        </div>
                        <div class="column is-one-quarter">
                            <p class="heading">Mata Pelajaran</p>
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

            <!-- ================= KARTU STATISTIK ================= -->
            <div class="columns is-multiline is-2 mb-5">

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-link" style="font-size: 2rem;"><i class="fas fa-users"></i></span>
                            <p class="title is-3 mb-0"><?= count($siswa) ?></p>
                            <p class="subtitle is-6">Total Siswa</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-info" style="font-size: 2rem;"><i class="fas fa-male"></i></span>
                            <p class="title is-3 mb-0"><?= $lakiLaki ?></p>
                            <p class="subtitle is-6">Laki-Laki</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-danger" style="font-size: 2rem;"><i class="fas fa-female"></i></span>
                            <p class="title is-3 mb-0"><?= $perempuan ?></p>
                            <p class="subtitle is-6">Perempuan</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-success" style="font-size: 2rem;"><i class="fas fa-percent"></i></span>
                            <p class="title is-3 mb-0"><?= $persentase ?>%</p>
                            <p class="subtitle is-6">Persentase Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-success" style="font-size: 2rem;"><i class="fas fa-check-circle"></i></span>
                            <p class="title is-3 mb-0"><?= $statistikMap['HADIR'] ?? 0 ?></p>
                            <p class="subtitle is-6">Hadir</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-info" style="font-size: 2rem;"><i class="fas fa-pen"></i></span>
                            <p class="title is-3 mb-0"><?= $statistikMap['IZIN'] ?? 0 ?></p>
                            <p class="subtitle is-6">Izin</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-warning" style="font-size: 2rem;"><i class="fas fa-thermometer-half"></i></span>
                            <p class="title is-3 mb-0"><?= $statistikMap['SAKIT'] ?? 0 ?></p>
                            <p class="subtitle is-6">Sakit</p>
                        </div>
                    </div>
                </div>

                <div class="column is-one-quarter">
                    <div class="card">
                        <div class="card-content has-text-centered">
                            <span class="icon has-text-danger" style="font-size: 2rem;"><i class="fas fa-user-times"></i></span>
                            <p class="title is-3 mb-0"><?= $statistikMap['ALPHA'] ?? 0 ?></p>
                            <p class="subtitle is-6">Alpha</p>
                        </div>
                    </div>
                </div>

            </div>

            <?php if (!empty($absensi)): ?>

                                                    <!-- ================= MATRIKS REKAP SISWA x TANGGAL ================= -->
                                                    <?php $isAkhirPekan = fn($h) => in_array($h, ['Sabtu', 'Minggu']); ?>
                                                    <div class="card mb-5">
                                                        <header class="card-header has-background-link-light">
                                                            <p class="card-header-title">
                                                                <span class="icon mr-2"><i class="fas fa-table"></i></span>
                                                                Rekap Kehadiran Siswa per Tanggal
                                                            </p>
                                                        </header>
                                                        <div class="card-content">
                                                            <div class="mb-3">
                                                                <span class="tag is-success">M = Masuk (Hadir)</span>
                                                                <span class="tag is-info">I = Izin</span>
                                                                <span class="tag is-warning">S = Sakit</span>
                                                                <span class="tag is-danger">A = Alpha</span>
                                                                <span class="tag is-light">- = Tidak ada absensi</span>
                                                            </div>
                                                            <div class="table-container">
                                                                <table class="table is-fullwidth is-striped is-hoverable is-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th rowspan="2" style="width: 50px;">No</th>
                                                                            <th rowspan="2">Nama Siswa</th>
                                                                            <th rowspan="2">NISN</th>
                                                                            <?php foreach ($kalender as $k): ?>
                                                                                        <th class="has-text-centered" style="<?= $isAkhirPekan($k['hari']) ? 'background: #f5f5f5;' : '' ?>">
                                                                                            <?= $k['hari'] ?>
                                                                                        </th>
                                                                            <?php endforeach; ?>
                                                                            <th rowspan="2" class="has-text-centered">Total Hadir</th>
                                                                            <th rowspan="2">Ringkasan</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <?php foreach ($kalender as $k): ?>
                                                                                        <th class="has-text-centered" style="<?= $isAkhirPekan($k['hari']) ? 'background: #f5f5f5;' : '' ?>">
                                                                                            <?= $k['tanggalAngka'] ?>
                                                                                        </th>
                                                                            <?php endforeach; ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $no = 1;
                                                                        foreach ($siswa as $row): ?>
                                                                                                                <?php
                                                                                                                $key = (string) $row['id'];
                                                                                                                $dataSiswa = $map[$key] ?? [];
                                                                                                                $hadirSiswa = 0;
                                                                                                                foreach ($dataSiswa as $st) {
                                                                                                                    if ($st === 'HADIR')
                                                                                                                        $hadirSiswa++;
                                                                                                                }
                                                                                                                $totalEntri = count($dataSiswa);
                                                                                                                if ($totalEntri === 0) {
                                                                                                                    $ringkasan = ['Belum Ada Data', 'is-light'];
                                                                                                                } elseif ($hadirSiswa === $totalEntri) {
                                                                                                                    $ringkasan = ['Semua Hadir', 'is-success'];
                                                                                                                } else {
                                                                                                                    $ringkasan = ['Ada Ketidakhadiran', 'is-warning'];
                                                                                                                }
                                                                                                                ?>
                                                                                                                <tr>
                                                                                                                    <td><?= $no++ ?></td>
                                                                                                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                                                                                                    <td><?= htmlspecialchars($row['nisn']) ?></td>
                                                                                                                    <?php foreach ($kalender as $k): ?>
                                                                                                                                <td class="has-text-centered" style="<?= $isAkhirPekan($k['hari']) ? 'background: #fafafa;' : '' ?>">
                                                                                                                                    <?php if (isset($dataSiswa[$k['tanggal']])): ?>
                                                                                                                                                <span class="tag <?= $warnaStatus[$dataSiswa[$k['tanggal']]] ?? 'is-light' ?>">
                                                                                                                                                    <?= $kodeStatus[$dataSiswa[$k['tanggal']]] ?? $dataSiswa[$k['tanggal']] ?>
                                                                                                                                                </span>
                                                                                                                                    <?php else: ?>
                                                                                                                                                <span class="has-text-grey-lighter">-</span>
                                                                                                                                    <?php endif; ?>
                                                                                                                                </td>
                                                                                                                    <?php endforeach; ?>
                                                                                                                    <td class="has-text-centered"><strong><?= $hadirSiswa ?></strong></td>
                                                                                                                    <td><span class="tag <?= $ringkasan[1] ?>"><?= $ringkasan[0] ?></span></td>
                                                                                                                </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="has-background-link-light">
                                                                            <td colspan="3"><strong>Jumlah Hadir</strong></td>
                                                                            <?php foreach ($kalender as $k): ?>
                                                                                        <?php
                                                                                        $hadirTgl = 0;
                                                                                        foreach ($siswa as $row) {
                                                                                            if (isset($map[(string) $row['id']][$k['tanggal']]) && $map[(string) $row['id']][$k['tanggal']] === 'HADIR')
                                                                                                $hadirTgl++;
                                                                                        }
                                                                                        ?>
                                                                                        <td class="has-text-centered"><strong><?= $hadirTgl ?></strong></td>
                                                                            <?php endforeach; ?>
                                                                            <td class="has-text-centered"><strong><?= $hadir ?></strong></td>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- ================= REKAP HARIAN ================= -->
                                                    <div class="card">
                                                        <header class="card-header has-background-link-light">
                                                            <p class="card-header-title">
                                                                <span class="icon mr-2"><i class="fas fa-calendar-alt"></i></span>
                                                                Rekap Kehadiran per Tanggal
                                                            </p>
                                                        </header>
                                                        <div class="card-content">
                                                            <div class="table-container">
                                                                <table class="table is-fullwidth is-striped is-hoverable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Tanggal</th>
                                                                            <?php foreach ($daftarStatus as $st): ?>
                                                                                                                    <th class="has-text-centered"><?= ucfirst(strtolower($st)) ?></th>
                                                                            <?php endforeach; ?>
                                                                            <th class="has-text-centered">Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($rekapHarianMap as $tgl => $perStatus): ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <?php [$y, $m, $d] = explode('-', $tgl); ?>
                                                                                                                        <strong><?= (int) $d ?>                                                                         <?= $bulanId[(int) $m - 1] ?>                                                                         <?= $y ?></strong>
                                                                                                                    </td>
                                                                                                                    <?php $jumlahTanggal = 0;
                                                                                                                    foreach ($daftarStatus as $st): ?>
                                                                                                                                                            <?php $jumlahTanggal += $perStatus[$st] ?? 0; ?>
                                                                                                                                                            <td class="has-text-centered">
                                                                                                                                                                <span class="tag <?= $warnaStatus[$st] ?? 'is-light' ?>"><?= $perStatus[$st] ?? 0 ?></span>
                                                                                                                                                            </td>
                                                                                                                    <?php endforeach; ?>
                                                                                                                    <td class="has-text-centered"><strong><?= $jumlahTanggal ?></strong></td>
                                                                                                                </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

            <?php else: ?>
                                                    <div class="notification is-light">
                                                        <strong>Belum ada data absensi untuk jadwal ini.</strong> Silakan tambahkan kehadiran siswa terlebih dahulu
                                                        melalui halaman <a href="/absensi/detail/<?= $jadwal['id'] ?>">Detail Absensi</a>.
                                                    </div>
            <?php endif; ?>

        </div>
    </section>

</body>
