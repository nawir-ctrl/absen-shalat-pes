<?php

namespace Tests\Feature;

use App\Exports\RekapAbsensiKegiatanExport;
use App\Exports\RekapAbsensiShalatExport;
use App\Exports\RekapRingkasanAbsensiShalatExport;
use App\Models\AbsensiKegiatanTambahan;
use App\Models\AbsensiShalat;
use App\Models\KegiatanTambahan;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekapExportAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengurus_exports_only_assigned_kelas(): void
    {
        [$pengurus, $kelasDiampu, $kelasLain, $kegiatan] = $this->makeAbsensiDataset();
        $allowedKelasIds = $pengurus->fresh()->kelasIdDiizinkan();

        $shalatRows = (new RekapAbsensiShalatExport(
            '2026-04-01',
            '2026-04-30',
            null,
            $allowedKelasIds
        ))->collection();

        $this->assertCount(1, $shalatRows);
        $this->assertSame('Santri Diampu', $shalatRows->first()['nama_santri']);
        $this->assertSame($kelasDiampu->nama, $shalatRows->first()['kelas']);

        $kegiatanRows = (new RekapAbsensiKegiatanExport(
            '2026-04-01',
            '2026-04-30',
            null,
            $kegiatan->id,
            $allowedKelasIds
        ))->collection();

        $this->assertCount(1, $kegiatanRows);
        $this->assertSame('Santri Diampu', $kegiatanRows->first()['nama_santri']);
        $this->assertSame($kelasDiampu->nama, $kegiatanRows->first()['kelas']);
    }

    public function test_pengurus_cannot_export_unassigned_kelas_by_query_parameter(): void
    {
        [$pengurus, , $kelasLain, $kegiatan] = $this->makeAbsensiDataset();
        $allowedKelasIds = $pengurus->fresh()->kelasIdDiizinkan();

        $shalatRows = (new RekapAbsensiShalatExport(
            '2026-04-01',
            '2026-04-30',
            $kelasLain->id,
            $allowedKelasIds
        ))->collection();

        $this->assertCount(0, $shalatRows);

        $kegiatanRows = (new RekapAbsensiKegiatanExport(
            '2026-04-01',
            '2026-04-30',
            $kelasLain->id,
            $kegiatan->id,
            $allowedKelasIds
        ))->collection();

        $this->assertCount(0, $kegiatanRows);
    }

    public function test_pdf_routes_require_absensi_roles(): void
    {
        $user = User::factory()->create(['role' => 'santri']);

        $this->actingAs($user)->get('/pdf-absensi-shalat')->assertForbidden();
        $this->actingAs($user)->get('/pdf-ringkasan-absensi-shalat')->assertForbidden();
        $this->actingAs($user)->get('/pdf-absensi-kegiatan')->assertForbidden();
        $this->actingAs($user)->get('/rekap-santri/pdf')->assertForbidden();
    }

    public function test_rekap_shalat_summary_is_ordered_by_kelas_then_student_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kelasB = Kelas::create(['nama' => 'Kelas B']);
        $kelasA = Kelas::create(['nama' => 'Kelas A']);

        $zaki = Santri::create([
            'nis' => 'S003',
            'nama' => 'Zaki',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasB->id,
            'status' => 'aktif',
        ]);
        $budi = Santri::create([
            'nis' => 'S002',
            'nama' => 'Budi',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasA->id,
            'status' => 'aktif',
        ]);
        $ahmad = Santri::create([
            'nis' => 'S001',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasA->id,
            'status' => 'aktif',
        ]);

        foreach ([
            [$ahmad, '2026-04-01', 'subuh', 'hadir'],
            [$ahmad, '2026-04-01', 'dzuhur', 'masbuk'],
            [$ahmad, '2026-04-01', 'ashar', 'alpha'],
            [$budi, '2026-04-01', 'subuh', 'sakit'],
        ] as [$santri, $tanggal, $waktu, $status]) {
            AbsensiShalat::create([
                'santri_id' => $santri->id,
                'tanggal' => $tanggal,
                'waktu_shalat' => $waktu,
                'status' => $status,
                'user_id' => $admin->id,
            ]);
        }

        $response = $this->actingAs($admin)->get(route('absensi-shalat.rekap', [
            'tanggal_mulai' => '2026-04-01',
            'tanggal_selesai' => '2026-04-30',
        ]));

        $response->assertOk();
        $response->assertSeeTextInOrder(['Ahmad', 'Budi', 'Zaki']);
        $response->assertSeeText('Rekap Shalat Per Santri');

        $rows = collect([
            (object) [
                'nis' => 'S001',
                'nama' => 'Ahmad',
                'kelas_nama' => 'Kelas A',
                'jumlah_hadir' => 1,
                'jumlah_masbuk' => 1,
                'jumlah_izin' => 0,
                'jumlah_sakit' => 0,
                'jumlah_alpha' => 1,
                'total_absensi' => 3,
            ],
        ]);

        $exportRows = (new RekapRingkasanAbsensiShalatExport($rows))->collection();

        $this->assertSame([
            'no' => 1,
            'nis' => 'S001',
            'nama_santri' => 'Ahmad',
            'kelas' => 'Kelas A',
            'hadir' => 1,
            'masbuk' => 1,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 1,
            'total' => 3,
        ], $exportRows->first());
    }

    private function makeAbsensiDataset(): array
    {
        $pengurus = User::factory()->create(['role' => 'pengurus']);
        $kelasDiampu = Kelas::create(['nama' => 'Kelas Diampu']);
        $kelasLain = Kelas::create(['nama' => 'Kelas Lain']);
        $pengurus->kelasDiampu()->sync([$kelasDiampu->id]);

        $santriDiampu = Santri::create([
            'nis' => 'S001',
            'nama' => 'Santri Diampu',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasDiampu->id,
            'status' => 'aktif',
        ]);

        $santriLain = Santri::create([
            'nis' => 'S002',
            'nama' => 'Santri Lain',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelasLain->id,
            'status' => 'aktif',
        ]);

        $kegiatan = KegiatanTambahan::create(['nama' => 'Tahfidz']);

        foreach ([$santriDiampu, $santriLain] as $santri) {
            AbsensiShalat::create([
                'santri_id' => $santri->id,
                'tanggal' => '2026-04-10',
                'waktu_shalat' => 'subuh',
                'status' => 'hadir',
                'user_id' => $pengurus->id,
            ]);

            AbsensiKegiatanTambahan::create([
                'santri_id' => $santri->id,
                'kegiatan_tambahan_id' => $kegiatan->id,
                'tanggal' => '2026-04-10',
                'status' => 'hadir',
                'user_id' => $pengurus->id,
            ]);
        }

        return [$pengurus, $kelasDiampu, $kelasLain, $kegiatan];
    }
}
