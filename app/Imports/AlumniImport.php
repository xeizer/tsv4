<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\User;
use App\Mahasiswam;
use Illuminate\Contracts\Queue\ShouldQueue;
use Session;

class AlumniImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {
        session()->forget(['hitung', 'gagal', 'namagagal']);
        $hitung = 0;
        $gagal = 0;
        $namagagal = '';
        //dd($rows);
        foreach ($rows as $row) {

            if ($row['nim'] != null) {
                if (!User::where('nim', $row['nim'])->first()) {
                    $user = new User();
                    $user->nim = $row['nim'];
                    $user->name = $row['nama_alumni'];
                    $user->email = $row['email_alumni'];
                    $user->password = bcrypt($row['password_alumni']);
                    $user->save();
                    $user->attachRole('mahasiswa');

                    $mahasiswa = new Mahasiswam();
                    $mahasiswa->user_id = User::select('id')->where('nim', $row['nim'])->first()->id;
                    $mahasiswa->prodim_id = $row['prodi'] + 1;
                    $mahasiswa->angkatan = $row['angkatan'];
                    $mahasiswa->ipk = $row['ipk'];
                    $mahasiswa->semester_lulus = $row['semester_lulus'];
                    $mahasiswa->tahun_lulus = $row['tahun_lulus'];
                    $mahasiswa->durasi_tahun = $row['durasi_kuliah_tahun'];
                    $mahasiswa->durasi_bulan = $row['durasi_kuliah_bulan'];
                    $mahasiswa->durasi_hari = $row['durasi_kuliah_hari'];
                    $mahasiswa->status = 1;
                    $mahasiswa->save();
                    $hitung++;
                } elseif (User::where('nim', $row['nim'])->first()) {
                    $gagal++;
                    $namagagal = $namagagal . " " . $row['nama_alumni'] . ", ";
                }
            } else {
                continue;
            }
        }
        session([
            'berhasil' => 'Import Berhasil = ' . $hitung,
            'kegagalan' => ' Duplikat = ' . $gagal . '<br /> nama:' . $namagagal,
        ]);
    }
    public function batchSize(): int
    {
        return 200;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
