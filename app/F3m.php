<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F3m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f301', 'f302', 'f303'];
    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswam', 'mahasiswam_id', 'id');
    }
}
