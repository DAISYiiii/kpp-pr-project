<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRequest extends Model
{
    use HasFactory;

    protected $table = 'news_requests';
    protected $primaryKey = 'news_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'department_id',
        'category_id',
        'district_id',
        'created_by',
        'title',
        'content',
        'detail',
        'status',
        'current_status', // เพิ่มฟิลด์นี้เพื่อให้บันทึกสถานะจากหน้าฟอร์มได้ถูกต้อง
        'admin_comment',
        'activity_date',
        'activity_time',
        'location',
        'objective',
        'target_group',
        'benefit',
        'contact_name',
        'contact_phone',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}