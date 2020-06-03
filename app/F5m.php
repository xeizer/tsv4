<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class F5m extends Model
{
    //
    protected $fillable = ['mahasiswam_id', 'f501', 'f502', 'f503'];
    public function total()
    {
        return $this->f502 + $this->f503;
    }
}
