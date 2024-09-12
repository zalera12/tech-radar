<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory,HasUlids;

    // Daftar kolom yang dapat diisi
    protected $fillable = [
        'id',
        'company_id',
        'category_id',
        'user_id',
        'name',
        'description',
        'is_new',
        'quadrant',
        'ring'
    ];

    // Relasi ke model Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relasi ke model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke model User (jika diperlukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
