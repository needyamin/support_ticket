<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
        'priority',
        'assigned_to',
    ];

    /**
     * The user who created the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The support staff assigned to the ticket.
     */
    public function assignedToUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Replies associated with the ticket.
     */
    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    /**
     * Attachments associated with the ticket.
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    
}
