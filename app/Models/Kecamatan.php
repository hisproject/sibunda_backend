<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    //
    protected $table = 'kecamatan';
    protected $fillable = ['nama', 'kota_id'];
    public $timestamps = false;

    public function kota() {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
