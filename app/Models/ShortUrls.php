<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrls extends Model
{
    protected $fillable = ['url', 'short_url', 'hits', 'companies_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Companies::class, 'companies_id');
    }
}
