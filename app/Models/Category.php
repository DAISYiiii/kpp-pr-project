<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
        'description',
    ];

    // เพิ่มความสัมพันธ์ไปยัง Model News เพื่อรองรับการทำ Dashboard Chart
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id', 'category_id');
    }
}