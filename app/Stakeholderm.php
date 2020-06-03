<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stakeholderm extends Model
{
    protected $fillable = ['user_id', 'mahasiswam_id'];
    //
    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswam', 'mahasiswam_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
