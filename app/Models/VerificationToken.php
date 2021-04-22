<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'password', 'token', 'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
