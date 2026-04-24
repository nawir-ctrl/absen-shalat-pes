<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $kelas = Kelas::where('nama', $row['kelas'])->first();

        if (!$kelas) {
            return null;
        }

        return new Santri([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'kelas_id' => $kelas->id,
            'status' => $row['status'] ?? 'aktif',
            'nama_wali' => $row['nama_wali'] ?? null,
            'no_hp_wali' => $row['no_hp_wali'] ?? null,
            'alamat' => $row['alamat'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nis' => ['required', 'string', 'max:50', Rule::unique('santris', 'nis')],
            '*.nama' => ['required', 'string', 'max:255'],
            '*.jenis_kelamin' => ['required', 'in:L,P'],
            '*.kelas' => ['required', 'string'],
            '*.status' => ['nullable', 'in:aktif,nonaktif'],
            '*.nama_wali' => ['nullable', 'string', 'max:255'],
            '*.no_hp_wali' => ['nullable', 'string', 'max:30'],
            '*.alamat' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nis.required' => 'Kolom NIS wajib diisi.',
            '*.nis.unique' => 'Ada NIS yang sudah terdaftar di database.',
            '*.nama.required' => 'Kolom nama wajib diisi.',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            '*.status.in' => 'Status harus aktif atau nonaktif.',
        ];
    }
}