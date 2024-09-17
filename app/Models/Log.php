<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory,HasUlids;

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class);
    }


}
