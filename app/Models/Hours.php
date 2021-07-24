<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hours extends Model
{
    use HasFactory;
    protected $dateFormat = 'd-m-Y';

    protected $table = 'hours';
    protected $fillable = [
        'workers_id',
        'contrahents_id',
        // 'contrahents_salary_cash',
        // 'contrahents_salary_invoice',
        // 'workers_price_hour',
        'work_day',
        'hours',
        'status_of_billings'
    ];
    public function workers()
    {
        return $this->belongsTo(Workers::class);
    }

    public function contrahents()
    {
        return $this->belongsTo(Contrahents::class);
    }
    public function logs(){
        return $this->hasMany(Logs::class);
    }

    public function hoursbill(){
        return $this->hasMany(Hoursbill::class);
    }
}