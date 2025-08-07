<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTicket extends Model
{
    protected $table = 'service_ticket';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'category_id',
        'subcategory_id',
        'status_id',
        'source_id',
        'assigned_to',
        'priority_id',
        'date_open',
        'date_close',
        'sla_minutes',
        'time_to_resolve',
        'sla_met'
    ];

    protected $casts = [
        'date_open' => 'datetime',
        'date_close' => 'datetime',
        'sla_met' => 'boolean',
    ];

    /**
     * Get the user who created this ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user assigned to this ticket
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category of this ticket
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Get the subcategory of this ticket
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(TicketSubcategory::class, 'subcategory_id');
    }

    /**
     * Get the status of this ticket
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    /**
     * Get the source of this ticket
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(TicketSource::class, 'source_id');
    }

    /**
     * Get the priority level of this ticket
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(PriorityLevel::class, 'priority_id');
    }
}
