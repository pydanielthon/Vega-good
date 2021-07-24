<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrahents extends Model
{
    use HasFactory;
    protected $table = 'contrahents';
    protected $fillable = [
        'name',
        'email',
        'token',
        'salary_cash',
        'salary_invoice',
        'status',
        'notes',
    ];

    public function hours(){
        return $this->hasMany(Hours::class);
    }
    public function billings(){
        return $this->hasMany(Billings::class);
    }
    public function logs(){
        return $this->hasMany(Logs::class);
    }
    public function hoursbill(){
        return $this->hasMany(Hoursbill::class);
    }
}