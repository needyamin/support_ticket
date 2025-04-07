<?php

namespace App\Http\Controllers;

use App\Models\Tricket;
use Illuminate\Http\Request;

class CreateTricketController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        return view('etricket.create_ticket.list');
    }

   
    public function create()
    {
        return view('etricket.create_ticket.create');
    }

  
    public function store(Request $request)
    {
        //
    }

   
    public function show(Tricket $tricket)
    {
        //
    }

    
    public function edit(Tricket $tricket)
    {
        //
    }

   
    public function update(Request $request, Tricket $tricket)
    {
        //
    }

 
    public function destroy(Tricket $tricket)
    {
        //
    }
}
