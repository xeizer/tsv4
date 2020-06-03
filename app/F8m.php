<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F8m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f8'];
    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswam', 'mahasiswam_id', 'id');
    }
}
