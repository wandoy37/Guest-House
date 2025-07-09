<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('room.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('room.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'class'     => 'required|string|max:255',
            'facility'  => 'nullable|string',
            'price'     => 'required',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hilangkan format titik dari input harga
        $cleanedPrice = preg_replace('/[^\d]/', '', $request->price);

        // Inisialisasi data room
        $room = new Room();
        $room->name = $validated['name'];
        $room->class = $validated['class'];
        $room->facility = $validated['facility'];
        $room->price = $cleanedPrice;
        $room->status = 'available';

        // Simpan foto jika ada
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('rooms', 'public'); // disimpan di storage/app/public/rooms
            $room->photo = $photoPath;
        }

        // Simpan ke database
        $room->save();

        return redirect()->route('room.index')->with('success', 'room was successfully saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }

        return response()->json($room);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::find($id);
        return view('room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:100',
            'facility' => 'nullable|string',
            'price' => 'required|string', // Nanti kita ubah ke integer
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_photo' => 'nullable|in:0,1',
        ]);

        // Convert price dari format rupiah (Rp 1.000.000) ke integer (1000000)
        $validated['price'] = (int) str_replace(['.', ','], '', $validated['price']);

        // Cek jika ada file foto baru di-upload
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($room->photo && Storage::exists('public/' . $room->photo)) {
                Storage::delete('public/' . $room->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('rooms', 'public');
            $validated['photo'] = $path;
        } elseif ($request->remove_photo == '1') {
            // Hapus foto jika user minta hapus
            if ($room->photo && Storage::exists('public/' . $room->photo)) {
                Storage::delete('public/' . $room->photo);
            }

            $validated['photo'] = null;
        } else {
            // Jangan ubah field photo jika tidak di-upload dan tidak diminta hapus
            unset($validated['photo']);
        }

        // Update data ke database
        $room->update($validated);

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);

        // Hapus file foto jika ada
        if ($room->photo && Storage::exists('public/' . $room->photo)) {
            Storage::delete('public/' . $room->photo);
        }

        // Hapus data room dari database
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }
}
