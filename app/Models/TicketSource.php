<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSource extends Model
{
    protected $table = 'ticket_source';

    protected $fillable = ['name'];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * Get tickets from this source
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class, 'source_id');
    }
}
