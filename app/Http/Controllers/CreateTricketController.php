<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;
use App\Notifications\TicketCreated;
use App\Notifications\TicketReplied;
use App\Notifications\TicketAttachmentAdded;
use App\Notifications\TicketStatusChanged;

class CreateTricketController extends Controller
{
    /**
     * Require authentication.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Helper: Check if current user is admin or moderator
     */
    protected function isAdminOrModerator()
    {
        $user = auth()->user();
        if (!$user) return false;
        // Accept both string and int for group, and trim spaces
        $group = is_string($user->group) ? strtolower(trim($user->group)) : $user->group;
        return in_array($group, ['admin', 'moderator'], true);
    }

    /**
     * Helper: Check if current user is admin
     */
    protected function isAdmin()
    {
        $user = auth()->user();
        if (!$user) return false;
        $group = is_string($user->group) ? strtolower(trim($user->group)) : $user->group;
        return $group === 'admin';
    }

    /**
     * Display a list of tickets.
     */
    public function index()
    {
        // Fetch tickets with related user info and paginate the results.
        $tickets = Ticket::with('user', 'assignedToUser')->paginate(10);
        return view('etricket.create_ticket.list', compact('tickets'));
    }

    /**
     * Show the form to create a new ticket.
     */
    public function create()
    {
        if (!$this->isAdminOrModerator()) {
            abort(403, 'Only admin or moderator can create tickets.');
        }
        return view('etricket.create_ticket.create');
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        if (!$this->isAdminOrModerator()) {
            abort(403, 'Only admin or moderator can create tickets.');
        }
        $data = $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'nullable|in:low,medium,high',
        ]);
        $data['user_id'] = auth()->id();
        $data['status']  = 'open';
        $ticket = Ticket::create($data);
        // Notify the assigned user (if any) or all admins/moderators
        if ($ticket->assigned_to) {
            $ticket->assignedToUser?->notify(new TicketCreated($ticket));
        } else {
            // Notify all admins/moderators
            \App\Models\User::whereIn('group', ['admin', 'moderator'])->get()->each(function($user) use ($ticket) {
                $user->notify(new TicketCreated($ticket));
            });
        }
        return redirect()->route('etricket.show', $ticket->id)
                         ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display a specific ticket along with its replies and attachments.
     */

    public function show($id)
    {
    $ticket = Ticket::with(['user', 'assignedToUser', 'replies.user', 'attachments'])->findOrFail($id);
    return view('etricket.create_ticket.show', compact('ticket'));
}


    /**
     * Show the form to edit an existing ticket.
     */
    public function edit(Ticket $ticket)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Only admin can edit tickets.');
        }
        $users = \App\Models\User::orderBy('name')->get();
        return view('etricket.create_ticket.edit', compact('ticket', 'users'));
    }

    /**
     * Update the specified ticket.
     */
    public function update(Request $request, Ticket $ticket)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Only admin can update tickets.');
        }
        $data = $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:open,pending,closed',
            'priority'    => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
        $oldAssignedTo = $ticket->assigned_to;
        $oldStatus = $ticket->status;
        $ticket->update($data);
        // Notify if assigned user changed
        if ($oldAssignedTo != $ticket->assigned_to && $ticket->assigned_to) {
            $assignedUser = $ticket->assignedToUser;
            if ($assignedUser && $assignedUser->id !== auth()->id()) {
                $assignedUser->notify(new \App\Notifications\TicketCreated($ticket));
            }
        }
        // Notify if status changed
        if ($oldStatus !== $data['status']) {
            $notifiables = collect([$ticket->user, $ticket->assignedToUser])->filter(function($user) {
                return $user && $user->id !== auth()->id();
            })->unique('id');
            foreach ($notifiables as $user) {
                $user->notify(new TicketStatusChanged($ticket, $oldStatus, $data['status']));
            }
        }
        return redirect()->route('etricket.show', $ticket->id)
                         ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket)
    {
        if (!$this->isAdmin()) {
            abort(403, 'Only admin can delete tickets.');
        }
        $ticket->delete();
        return redirect()->route('etricket.index')
                         ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Add a reply to a ticket.
     */
    public function addReply(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'message' => 'required|string'
        ]);

        $data['ticket_id'] = $ticket->id;
        $data['user_id']   = auth()->id();

        $reply = TicketReply::create($data);

        // Notify ticket owner, assigned user, and all admins (except the replier)
        $adminUsers = \App\Models\User::where('group', 'admin')->get();
        $notifiables = collect([$ticket->user, $ticket->assignedToUser])
            ->merge($adminUsers)
            ->filter(function($user) {
                return $user && $user->id !== auth()->id();
            })->unique('id');
        foreach ($notifiables as $user) {
            $user->notify(new TicketReplied($reply));
        }

        return redirect()->route('etricket.show', $ticket->id)
                         ->with('success', 'Reply added successfully.');
    }



    public function addAttachment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'attachment' => 'required',
            'attachment.*' => 'file|max:20480|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/svg+xml,application/pdf,video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/mpeg',
        ], [
            'attachment.*.mimetypes' => 'Only images (jpeg, png, gif, webp, bmp, svg), PDF, and video files (mp4, mov, avi, wmv, mpeg) are allowed.',
            'attachment.*.max' => 'Each file must not exceed 20MB.'
        ]);

        if ($request->hasFile('attachment')) {
            $files = $request->file('attachment');
            $directory = public_path('ticket_attachments');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($directory, $filename);
                    $attachment = TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'user_id'   => auth()->id(),
                        'file_path' => 'ticket_attachments/' . $filename,
                    ]);
                    // Notify ticket owner and assigned user (except the uploader)
                    $notifiables = collect([$ticket->user, $ticket->assignedToUser])->filter(function($user) {
                        return $user && $user->id !== auth()->id();
                    })->unique('id');
                    foreach ($notifiables as $user) {
                        $user->notify(new TicketAttachmentAdded($attachment));
                    }
                }
            }
            return redirect()->route('etricket.show', $ticket->id)
                             ->with('success', 'Attachments added successfully.');
        }
        return back()->withErrors(['attachment' => 'File upload failed. Please try again.']);
    }
    
    
    
    
}
