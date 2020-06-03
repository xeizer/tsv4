<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F15m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f15'];
    public function mahasiswa()
    {
        return $this->hasOne('App\Mahasiswam', 'id', 'mahasiswam_id');
    }
}
