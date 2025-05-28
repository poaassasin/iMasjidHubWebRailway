<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    // Ambil semua data inventaris
    public function index()
    {
        $data = Inventaris::all();

        return response()->json([
            'message' => 'Data inventaris',
            'data' => $data
        ]);
    }

    // Tambah data inventaris
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'kategori'    => 'required|string',
            'jumlah'      => 'required|integer',
        ]);

        $data = Inventaris::create($request->all());

        return response()->json([
            'message' => 'Inventaris ditambahkan',
            'data' => $data
        ], 201);
    }

    // (Opsional) Hapus inventaris
    public function destroy($id)
    {
        $item = Inventaris::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Inventaris dihapus']);
    }
}
