<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['token', 'expire_at', 'is_blacklist'];

    protected function casts()
    {
        return [
            'expire_at' => 'datetime',
        ];
    }

    public function scopeIsExpire($query)
    {
        return $query->whereDate('expire_at', '<=', now());
    }
}
