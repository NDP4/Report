<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSubcategory extends Model
{
    protected $table = 'ticket_subcategory';

    protected $fillable = [
        'category_id',
        'name'
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * Get the category that owns this subcategory
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Get tickets for this subcategory
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class, 'subcategory_id');
    }
}
