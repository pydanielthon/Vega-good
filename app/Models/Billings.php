<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billings extends Model
{
    protected $table = 'billings';
    protected $fillable = [
        'workers_id',
        'contrahents_id',
        'token',
        'category_id',
        'date',
        'price',
        'notes',
    ];

    public function workers()
    {
        return $this->hasMany(Workers::class);
    }

    public function contrahents()
    {
        return $this->belongsTo(Contrahents::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function logs(){
        return $this->hasMany(Logs::class);
    }
}

