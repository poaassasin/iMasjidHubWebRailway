<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalShalat;
use Illuminate\Support\Facades\Validator;

class JadwalShalatController extends Controller
{
    // Ambil semua jadwal shalat
    public function index()
    {
        $jadwal = JadwalShalat::orderBy('tanggal', 'asc')->get();

        return response()->json([
            'message' => 'Data semua jadwal shalat',
            'data' => $jadwal
        ]);
    }

    // Ambil jadwal shalat hari ini
    public function today()
    {
        $today = now()->toDateString(); // lebih modern dan tepat daripada date()
        $jadwal = JadwalShalat::where('tanggal', $today)->get();

        return response()->json([
            'message' => 'Jadwal shalat hari ini',
            'data' => $jadwal
        ]);
    }

    // Tambah jadwal baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'waktu_shalat' => 'required|string|max:50',
            'jam' => 'required|date_format:H:i',
            'imam' => 'nullable|string|max:100',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal = JadwalShalat::create($validator->validated());

        return response()->json([
            'message' => 'Jadwal shalat berhasil ditambahkan',
            'data' => $jadwal
        ], 201);
    }

    // Update jadwal shalat
    public function update(Request $request, $id)
    {
        $jadwal = JadwalShalat::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'waktu_shalat' => 'sometimes|required|string|max:50',
            'jam' => 'sometimes|required|date_format:H:i',
            'imam' => 'nullable|string|max:100',
            'tanggal' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal->update($validator->validated());

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $jadwal = JadwalShalat::find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus']);
    }
}
