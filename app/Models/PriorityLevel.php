<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriorityLevel extends Model
{
    protected $table = 'priority_level';

    protected $fillable = [
        'level_name',
        'level_value'
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * Get tickets with this priority level
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class, 'priority_id');
    }
}
