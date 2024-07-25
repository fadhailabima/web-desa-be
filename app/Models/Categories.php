<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'kategori',
    ];

    protected $guarded = [];

    public function facilities()
    {
        return $this->hasMany(Facilities::class, 'category_id', 'id');
    }
}
