<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodim extends Model
{
    //{
    protected $table = 'prodims';

    public function fakultas()
    {
        return $this->belongsTo('App\Fakultasm', 'fakultasm_id', 'id');
    }
    public function mahasiswa()
    {
        return $this->hasOne('App\Mahasiswam');
    }
}
