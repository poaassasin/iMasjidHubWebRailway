<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use Carbon\Carbon;

class AcaraController extends Controller
{
    // Tampilkan semua acara
    public function index()
    {
        $acara = Acara::orderBy('tanggal', 'asc')->get();

        return response()->json([
            'message' => 'Daftar semua acara',
            'data' => $acara
        ]);
    }

    // Tampilkan acara yang akan datang saja
    public function upcoming()
    {
        $today = Carbon::today();
        $acara = Acara::where('tanggal', '>=', $today)->orderBy('tanggal', 'asc')->get();

        return response()->json([
            'message' => 'Acara yang akan datang',
            'data' => $acara
        ]);
    }

    // Tambah acara
    public function store(Request $request)
    {
        $request->validate([
            'nama_acara' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
        ]);

        $acara = Acara::create($request->all());

        return response()->json([
            'message' => 'Acara berhasil ditambahkan',
            'data' => $acara
        ], 201);
    }

    // Update
    public function update(Request $request, $id)
    {
        $acara = Acara::find($id);

        if (!$acara) {
            return response()->json(['message' => 'Acara tidak ditemukan'], 404);
        }

        $acara->update($request->all());

        return response()->json([
            'message' => 'Acara berhasil diperbarui',
            'data' => $acara
        ]);
    }

    // Hapus
    public function destroy($id)
    {
        $acara = Acara::find($id);

        if (!$acara) {
            return response()->json(['message' => 'Acara tidak ditemukan'], 404);
        }

        $acara->delete();

        return response()->json(['message' => 'Acara berhasil dihapus']);
    }
}
