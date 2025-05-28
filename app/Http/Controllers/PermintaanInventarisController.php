<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanInventaris;

class PermintaanInventarisController extends Controller
{
    public function index()
    {
        $data = PermintaanInventaris::latest()->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminta' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'jam' => 'required|date_format:H:i:s',
        ]);

        $data = PermintaanInventaris::create($validated);

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $data = PermintaanInventaris::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = PermintaanInventaris::findOrFail($id);

        $validated = $request->validate([
            'nama_peminta' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'jam' => 'required|date_format:H:i:s',
        ]);

        $data->update($validated);

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = PermintaanInventaris::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data permintaan berhasil dihapus.']);
    }
}
