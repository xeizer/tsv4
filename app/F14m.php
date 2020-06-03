<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F14m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f14',];
    public function mahasiswa()
    {
        return $this->hasOne('App\Mahasiswam', 'id', 'mahasiswam_id');
    }
}
