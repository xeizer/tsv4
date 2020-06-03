<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fakultasm extends Model
{
    //
    public function prodi(){
        return $this->hasOne('App\Prodim');
    }
}
