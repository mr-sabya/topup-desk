<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active'
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}
