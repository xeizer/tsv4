<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F2m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f21', 'f22', 'f23', 'f24', 'f25', 'f26', 'f27',];
    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswam');
    }
}
