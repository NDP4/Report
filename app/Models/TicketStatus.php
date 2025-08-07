<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    protected $table = 'ticket_status';

    protected $fillable = ['name'];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * Get tickets with this status
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class, 'status_id');
    }
}
