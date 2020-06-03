<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adminm extends Model
{
    //
    protected $fillable = [
        'id', 'user_id', 'prodim_id',
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function prodi(){
        return $this->belongsTo('App\Prodim','prodim_id','id');
    }
}
