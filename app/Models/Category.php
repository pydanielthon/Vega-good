<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fillable = [
        'name',

    ];

    public function category()
    {
        return $this->hasMany(Billings::class);

    }
    public function logs()
    {
        return $this->hasMany(Logs::class);

    }
}
