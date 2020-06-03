<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F13m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f131', 'f132', 'f133',];

    public function mahasiswa()
    {
        return $this->belongsTo('App\mahasiswam', 'mahasiswam_id', 'id');
    }
}
