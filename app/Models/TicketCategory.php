<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    protected $table = 'ticket_category';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

    /**
     * Get subcategories for this category
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(TicketSubcategory::class, 'category_id');
    }

    /**
     * Get tickets for this category
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class, 'category_id');
    }
}
