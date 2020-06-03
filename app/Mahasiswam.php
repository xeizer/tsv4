<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswam extends Model
{
    //
    protected $fillable = [
        'user_id', 'prodim_id', 'ipk', 'semester_lulus', 'tahun_lulus', 'status', 'durasi_tahun', 'durasi_bulan', 'durasi_hari'
    ];

    public function scopeTercepat($query, $angkatan, $pilih)
    {
        $durasi = $query->select('durasi_tahun', 'durasi_bulan', 'durasi_hari')->where('angkatan', $angkatan)->get();
        $terkecil = 9999;
        $tahunterpilih = '-';
        $bulanterpilih = '-';
        $hariterpilih = '-';
        foreach ($durasi as $d) {
            $tahunhari = $d->durasi_tahun * 365;
            $tahunbulan = $d->durasi_bulan * 30;
            $totalhari = $d->durasi_hari + $tahunhari + $tahunbulan;
            if ($totalhari != 0) {
                if ($totalhari < $terkecil) {
                    $terkecil = $totalhari;
                    $tahunterpilih = $d->durasi_tahun;
                    $bulanterpilih = $d->durasi_bulan;
                    $hariterpilih = $d->durasi_hari;
                }
            }
        }
        if ($pilih == 'tahun') {
            return  $tahunterpilih;
        } elseif ($pilih == 'bulan') {
            return  $bulanterpilih;
        } else {
            return  $hariterpilih;
        }
    }
    public function scopeTerlama($query, $angkatan, $pilih)
    {
        $durasi = $query->select('durasi_tahun', 'durasi_bulan', 'durasi_hari')->where('angkatan', $angkatan)->get();
        $terkecil = 0;
        $tahunterpilih = '-';
        $bulanterpilih = '-';
        $hariterpilih = '-';
        foreach ($durasi as $d) {
            $tahunhari = $d->durasi_tahun * 365;
            $tahunbulan = $d->durasi_bulan * 30;
            $totalhari = $d->durasi_hari + $tahunhari + $tahunbulan;
            if ($totalhari != 0) {
                if ($totalhari > $terkecil) {
                    $terkecil = $totalhari;
                    $tahunterpilih = $d->durasi_tahun;
                    $bulanterpilih = $d->durasi_bulan;
                    $hariterpilih = $d->durasi_hari;
                }
            }
        }
        if ($pilih == 'tahun') {
            return  $tahunterpilih;
        } elseif ($pilih == 'bulan') {
            return  $bulanterpilih;
        } else {
            return  $hariterpilih;
        }
    }
    public function scopeRerata($query, $angkatan, $durasipilihan)
    {
        $durasi = $query->select($durasipilihan)->where('angkatan', $angkatan)->where($durasipilihan, '>', 0)->avg($durasipilihan);
        $tahun = (int) $durasi;
        return $tahun;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function prodi()
    {
        return $this->belongsTo('App\Prodim', 'prodim_id', 'id');
    }
    public function f2()
    {
        return $this->hasOne('App\F2m');
    }
    public function f3()
    {
        return $this->hasOne('App\F3m', 'mahasiswam_id', 'id');
    }
    public function f4()
    {
        return $this->hasOne('App\F4m');
    }
    public function f5()
    {
        return $this->hasOne('App\F5m');
    }
    public function f6()
    {
        return $this->hasOne('App\F6m');
    }
    public function f7()
    {
        return $this->hasOne('App\F7m');
    }
    public function f7a()
    {
        return $this->hasOne('App\F7am');
    }
    public function f8()
    {
        return $this->hasOne('App\F8m');
    }
    public function f9()
    {
        return $this->hasOne('App\F9m');
    }
    public function f10()
    {
        return $this->hasOne('App\F10m');
    }
    public function f11()
    {
        return $this->hasOne('App\F11m');
    }
    public function f12()
    {
        return $this->hasOne('App\F12m');
    }
    public function f13()
    {
        return $this->hasOne('App\F13m');
    }
    public function f14()
    {
        return $this->hasOne('App\F14m');
    }
    public function f15()
    {
        return $this->hasOne('App\F15m');
    }
    public function f16()
    {
        return $this->hasOne('App\F16m');
    }
    public function f17a()
    {
        return $this->hasOne('App\F17am');
    }
    public function f17b()
    {
        return $this->hasOne('App\F17bm');
    }
}
