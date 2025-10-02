<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sifen extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fact()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }
}
