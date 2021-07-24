<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    use HasFactory;

    protected $table = 'workers';
    protected $fillable = [
        'name',
        'surname',
        'price_hour',
        'address',
        'notes',
        'status',
        'date_of_billings',
        'token'

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