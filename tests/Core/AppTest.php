<?php

namespace App\Tests\Core;

use PHPUnit\Framework\TestCase;
use App\Core\App;

class AppTest extends TestCase
{
    private $urlAsli;

    protected function setUp(): void
    {
        $this->urlAsli = $_GET['url'] ?? null;
    }

    protected function tearDown(): void
    {
        if ($this->urlAsli === null) {
            unset($_GET['url']);
        } else {
            $_GET['url'] = $this->urlAsli;
        }
    }

    // Membuat instance App TANPA menjalankan konstruktor (agar parseURL bisa diuji murni)
    private function buatInstanceTanpaKonstruktor(): App
    {
        $ref = new \ReflectionClass(App::class);
        return $ref->newInstanceWithoutConstructor();
    }

    public function testParseURLMengembalikanArraySegmen(): void
    {
        $_GET['url'] = 'siswa/detail/5';

        $app = $this->buatInstanceTanpaKonstruktor();

        $this->assertSame(['siswa', 'detail', '5'], $app->parseURL());
    }

    public function testParseURLMenghilangkanGarisMiringAkhir(): void
    {
        $_GET['url'] = 'siswa/';

        $app = $this->buatInstanceTanpaKonstruktor();

        $this->assertSame(['siswa'], $app->parseURL());
    }

    public function testParseURLMenghilangkanGarisMiringGandaDiAkhir(): void
    {
        $_GET['url'] = 'absensi/detail/3/';

        $app = $this->buatInstanceTanpaKonstruktor();

        $this->assertSame(['absensi', 'detail', '3'], $app->parseURL());
    }

    public function testParseURLMengembalikanKosongSaatTidakAdaUrl(): void
    {
        unset($_GET['url']);

        $app = $this->buatInstanceTanpaKonstruktor();

        $this->assertSame([], $app->parseURL());
    }

    public function testKonstruktorMenjalankanControllerDefaultSaatTanpaUrl(): void
    {
        unset($_GET['url']);

        ob_start();
        new App();
        $output = ob_get_clean();

        $this->assertStringContainsString('Pilih Menu', $output);
        $this->assertStringContainsString('<title>Beranda</title>', $output);
    }

    public function testKonstruktorMenjalankanControllerDariUrl(): void
    {
        $_GET['url'] = 'kelas';

        ob_start();
        new App();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Kelas</title>', $output);
        $this->assertStringContainsString('Daftar Kelas', $output);
    }

    public function testKonstruktorMenjalankanControllerHomeDenganHurufKecil(): void
    {
        $_GET['url'] = 'home';

        ob_start();
        new App();
        $output = ob_get_clean();

        $this->assertStringContainsString('Pilih Menu', $output);
    }

    public function testKonstruktorMenjalankanControllerSiswaDenganParam(): void
    {
        $_GET['url'] = 'siswa';

        ob_start();
        new App();
        $output = ob_get_clean();

        $this->assertStringContainsString('<title>Daftar Siswa</title>', $output);
    }
}
