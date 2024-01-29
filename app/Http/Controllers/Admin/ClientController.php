<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all(['id', 'first_name', 'last_name', 'email','phone_number']);
    }

    public function create()
    {
        // Return a view for creating a new client
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients|max:255',
            'phone_number' => 'required|string|max:255',

        ]);

        // Create a new client in the database
        $client = Client::create($request->all());

        // Redirect to the index page with a success message
        return redirect()->route('admin.clients')->with('success', 'Client created successfully');
    }

    public function show(Client $client)
    {
        // Return a view to show details of a specific client
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        // Return a view for editing a specific client
        
            return $client;
    }

    public function update(Request $request, Client $client)
    {
        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $client->id,
            'phone_number' => 'required|string|max:255'
        ]);
    
        // Update the client in the database
        $client->update($request->all());
    
        // Redirect to the index page with a success message
        return redirect()->route('clients.index')->with('success', 'Client updated successfully');
    }
    

    public function destroy(Client $client)
    {
        // Delete the client from the database
        $client->delete();

        // Redirect to the index page with a success message
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }
}
