<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    //
    protected $table = 'kota';
    protected $fillable = ['nama', 'longitude', 'latitude', 'provinsi_id'];
    public $timestamps = false;

    public function provinsi() {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}
