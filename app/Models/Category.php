<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,HasUlids;

    // Daftar kolom yang dapat diisi
    protected $fillable = [
        'id', 
        'company_id',
        'name',
        'description'
    ];

    // Relasi ke model Company (jika ada)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relasi ke model Technology
    public function technologies()
    {
        return $this->hasMany(Technology::class);
    }
}
