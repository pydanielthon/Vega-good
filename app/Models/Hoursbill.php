<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hoursbill extends Model
{
    use HasFactory;
    protected $dateFormat = 'Y-m-d';

    protected $table = 'hoursbill';
    protected $fillable = [
        'date_from',
        'date_to',
        'deposit',
        'workers_id',
        'contrahents_id',
        'hours',
        'salary',
        '_token',

    ];
    public function workers()
    {
        return $this->belongsTo(Workers::class);
    }

    public function contrahents()
    {
        return $this->belongsTo(Contrahents::class);
    }
    public function hours()
    {
        return $this->belongsTo(Hours::class);
    }
}