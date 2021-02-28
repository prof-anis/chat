<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function getCreatedAtAttribute($val)
    {
        return Carbon::parse($val)->format('m/d/y');
    }

}
