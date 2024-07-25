<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'catlocs_id',
    ];

    protected $guarded = [];

    public function catlocs()
    {
        return $this->belongsTo(Catlocs::class, 'catlocs_id', 'id');
    }
}
