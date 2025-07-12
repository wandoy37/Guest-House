<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guests = Guest::all();
        return view('guest.index', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guest.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'type'      => 'required|string|max:255',
            'identity_number'     => 'required|string|max:255',
            'name'     => 'required|string|max:255',
            'address'     => 'required|string',
            'email'     => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
        ]);

        // Inisialisasi data guest
        $guest = new Guest();
        $guest->type = $validated['type'];
        $guest->identity_number = $validated['identity_number'];
        $guest->name = $validated['name'];
        $guest->address = $validated['address'];
        $guest->email = $validated['email'];
        $guest->phone = $validated['phone'];

        // Simpan ke database
        $guest->save();

        return redirect()->route('guest.index')->with('success', 'Guest was successfully saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guest = Guest::find($id);
        return view('guest.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guest = Guest::findOrFail($id);

        // Validasi data
        $validated = $request->validate([
            'type'      => 'required|string|max:255',
            'identity_number'     => 'required|string|max:255',
            'name'     => 'required|string|max:255',
            'address'     => 'required|string',
            'email'     => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
        ]);

        // Update data ke database
        $guest->update($validated);

        return redirect()->route('guest.index')->with('success', 'Guest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guest = Guest::findOrFail($id);

        // Hapus data guest dari database
        $guest->delete();

        return redirect()->route('guest.index')->with('success', 'Guest deleted successfully.');
    }
}
