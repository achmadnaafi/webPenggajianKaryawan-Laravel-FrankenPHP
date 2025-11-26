<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        $bulan_list = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        $gaji = Gaji::with('karyawan')
            ->orderByDesc('tahun')
            ->orderByRaw("FIELD(bulan,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->get();

        $karyawan = Karyawan::orderBy('nama')->get();

        return view('gaji-karyawan.index', compact('gaji', 'bulan_list', 'karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:data_karyawan,id',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'potongan' => 'required|numeric|min:0',
        ]);

        // Cek duplikat
        if (Gaji::where('id_karyawan', $request->id_karyawan)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists()) {
            return response()->json(['error' => 'Gaji untuk periode ini sudah ada'], 422);
        }

        $total = max(0, $request->gaji_pokok + $request->tunjangan - $request->potongan);

        Gaji::create([
            'id_karyawan' => $request->id_karyawan,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
            'potongan' => $request->potongan,
            'total_gaji' => $total,
        ]);

        return response()->json(['success' => 'Data berhasil ditambahkan']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_gaji' => 'required|exists:gaji_karyawan,id_gaji',
            'bulan' => 'required|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'potongan' => 'required|numeric|min:0',
        ]);

        $gaji = Gaji::findOrFail($request->id_gaji);

        // Cek duplikat kecuali diri sendiri
        if (Gaji::where('id_karyawan', $gaji->id_karyawan)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('id_gaji', '<>', $gaji->id_gaji)
                ->exists()) {
            return response()->json(['error' => 'Gaji untuk periode ini sudah ada'], 422);
        }

        $total = max(0, $request->gaji_pokok + $request->tunjangan - $request->potongan);

        $gaji->update([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
            'potongan' => $request->potongan,
            'total_gaji' => $total,
        ]);

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_gaji' => 'required|exists:gaji_karyawan,id_gaji',
        ]);

        $gaji = Gaji::findOrFail($request->id_gaji);
        $gaji->delete();

        return response()->json(['success' => 'Data berhasil dihapus']);
    }
}
