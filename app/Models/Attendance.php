<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    // These are the columns in your database table
    protected $fillable = [
        'user_id',
        'check_in',
        'status',
    ];

    /**
     * Relationship: Each attendance log belongs to one User (Employee)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}