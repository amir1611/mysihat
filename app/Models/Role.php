<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gaurd_name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class); // Adjust according to your relationship
    }
}
