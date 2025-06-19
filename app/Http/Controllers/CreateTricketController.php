<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\TicketAttachment;

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
        return auth()->user() && in_array(auth()->user()->group, ['admin', 'moderator']);
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
        return view('etricket.create_ticket.edit', compact('ticket'));
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
        $ticket->update($data);
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

        TicketReply::create($data);

        return redirect()->route('etricket.show', $ticket->id)
                         ->with('success', 'Reply added successfully.');
    }



    public function addAttachment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'attachment' => 'required|file|max:2048'
        ]);
    
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $directory = public_path('ticket_attachments');  // Public folder for attachment storage
    
            // Ensure the directory exists and has correct permissions
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);  // Creates directory with proper permissions
            }
    
            // Generate a unique filename
            $filename = uniqid() . '_' . $file->getClientOriginalName();
    
            // Move the file to the public/ticket_attachments folder
            $filePath = $file->move($directory, $filename);
    
            // Save the file path to the database
            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'user_id'   => auth()->id(),
                'file_path' => 'ticket_attachments/' . $filename,  // Save relative path
            ]);
    
            return redirect()->route('etricket.show', $ticket->id)
                             ->with('success', 'Attachment added successfully.');
        }
    
        return back()->withErrors(['attachment' => 'File upload failed. Please try again.']);
    }
    
    
    
    
}
