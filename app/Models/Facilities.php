<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilities extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'title',
        'titleSm',
        'subtitleSm',
        'image',
        'content',
        'category_id',
    ];

    protected $guarded = [];

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
