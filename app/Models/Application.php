<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasUuids;

    protected $fillable = [
        'lead_id', 'user_id', 'name', 'description', 'status',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
