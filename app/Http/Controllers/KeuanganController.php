<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    // GET /api/keuangan
    public function index()
    {
        $data = Keuangan::orderBy('tanggal', 'desc')->get();
        return response()->json($data);
    }

    // POST /api/keuangan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'kategori'  => 'required|string|max:100',
            'jumlah'    => 'required|numeric|min:0',
            'tanggal'   => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $record = Keuangan::create($validated);

        return response()->json($record, 201);
    }

    // GET /api/keuangan/{id}
    public function show($id)
    {
        $record = Keuangan::findOrFail($id);
        return response()->json($record);
    }

    // PUT /api/keuangan/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'kategori'  => 'required|string|max:100',
            'jumlah'    => 'required|numeric|min:0',
            'tanggal'   => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        $record = Keuangan::findOrFail($id);
        $record->update($validated);

        return response()->json($record);
    }

    // DELETE /api/keuangan/{id}
    public function destroy($id)
    {
        Keuangan::destroy($id);
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    // GET /api/keuangan/rekap
    public function rekap()
    {
        $pemasukan   = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $pengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo       = $pemasukan - $pengeluaran;

        return response()->json([
            'total_pemasukan'   => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'saldo'             => $saldo,
        ]);
    }
}
