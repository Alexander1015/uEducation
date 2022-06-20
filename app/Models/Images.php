<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    //Relación uno a muchos inversa
    public function topics()
    {
        return $this->belongsTo(Topic::class);
    }
}
