<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'type', 'name', 'email', 'phone', 'address', 'status',
    ];

    public function maintainers()
    {
        return $this->hasMany(LeadMaintainer::class, 'lead_id');
    }

    public function maintainer()
    {
        return $this->hasOne(LeadMaintainer::class, 'lead_id');
    }

    public function counselor()
    {
        return $this->belongsToMany(User::class, 'lead_maintainers', 'lead_id', 'user_id');
    }
}
