<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catlocs extends Model
{
    use HasFactory;

    protected $table = 'catlocs';

    protected $fillable = [
        'kategori_lokasi',
    ];

    protected $guarded = [];

    public function locations()
    {
        return $this->hasMany(Locations::class, 'catlocs_id', 'id');
    }
}
