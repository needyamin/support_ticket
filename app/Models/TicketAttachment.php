<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'file_path',
    ];

    /**
     * The ticket to which this attachment belongs.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The user who uploaded the attachment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
