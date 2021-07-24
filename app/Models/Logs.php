<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Logs extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'worker_id',
        'contrahent_id',
        'hour_id',
        'billing_id',
    ];

    public function billings(){
        return $this->belongsTo(Billings::class);
    }
    public function users(){
        return $this->belongsTo(User::class);
    }
    public function workers(){
        return $this->belongsTo(Workers::class);
    }
    public function hours(){
        return $this->belongsTo(Hours::class);
    }
    public function contrahents(){
        return $this->belongsTo(Contrahents::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}