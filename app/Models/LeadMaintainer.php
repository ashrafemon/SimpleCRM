<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LeadMaintainer extends Model
{
    use HasUuids;

    protected $fillable = [
        'lead_id', 'user_id', 'status',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'lead_id', 'lead_id');
    }
}
