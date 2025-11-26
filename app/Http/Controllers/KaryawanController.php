<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // generate one-time edit tokens (mirip native)
        $karyawans = Karyawan::all();
        $editTokens = [];
        foreach ($karyawans as $k) {
            $token = bin2hex(random_bytes(16));
            $editTokens[$token] = [
                'id' => $k->id,
                'expires' => now()->addMinutes(5)->timestamp
            ];
        }
        session(['edit_tokens' => $editTokens]);

        return view('data-karyawan.index', compact('karyawans'));
    }

    public function store(Request $request)
    {
        // =======================
        // VALIDASI CSRF
        // =======================
        $request->validate([
            '_token' => 'required'
        ]);

        $nama    = trim($request->nama);
        $jabatan = trim($request->jabatan);
        $alamat  = trim($request->alamat);
        $no_telp = trim($request->no_telp);

        // =======================
        // VALIDASI INPUT MIRIP NATIVE
        // =======================
        if (!preg_match('/^[a-zA-Z\s]+$/', $nama)) {
            return "error: Nama hanya boleh huruf dan spasi";
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $jabatan)) {
            return "error: Jabatan hanya boleh huruf dan spasi";
        }
        if (!preg_match('/^[a-zA-Z0-9\s\.,\-\/#]{3,}$/', $alamat)) {
            return "error: Alamat tidak valid";
        }
        if (!preg_match('/^628\d{7,10}$/', $no_telp)) {
            return "error: Nomor telepon harus diawali 628";
        }

        // CEK DUPLIKAT NO TELP
        if (Karyawan::where('no_telp', $no_telp)->exists()) {
            return "error: Nomor telepon sudah terdaftar";
        }

        // SIMPAN
        $k = new Karyawan();
        $k->nama = $nama;
        $k->jabatan = $jabatan;
        $k->alamat = $alamat;
        $k->no_telp = $no_telp;
        if ($k->save()) return "success";
        return "error: Gagal menyimpan data";
    }

    public function update(Request $request)
    {
        $token = $request->ftoken ?? '';
        $entry = session('edit_tokens')[$token] ?? null;

        if (!$entry || $entry['expires'] < now()->timestamp) {
            return "error: Token tidak valid atau kadaluarsa";
        }

        $id = $entry['id'];
        // hapus token setelah digunakan
        $editTokens = session('edit_tokens');
        unset($editTokens[$token]);
        session(['edit_tokens' => $editTokens]);

        $nama    = trim($request->nama);
        $jabatan = trim($request->jabatan);
        $alamat  = trim($request->alamat);
        $no_telp = trim($request->no_telp);

        if (!preg_match('/^[a-zA-Z\s]+$/', $nama)) return "error: Nama hanya boleh huruf dan spasi";
        if (!preg_match('/^[a-zA-Z\s]+$/', $jabatan)) return "error: Jabatan hanya boleh huruf dan spasi";
        if (!preg_match('/^[a-zA-Z0-9\s\.,\-\/#]{3,}$/', $alamat)) return "error: Alamat tidak valid";
        if (!preg_match('/^628\d{7,10}$/', $no_telp)) return "error: Nomor telepon harus diawali 628";

        $k = Karyawan::find($id);
        if (!$k) return "error: Karyawan tidak ditemukan";

        // cek no_telp duplicate
        if (Karyawan::where('no_telp', $no_telp)->where('id', '<>', $id)->exists()) {
            return "error: Nomor telepon sudah terdaftar";
        }

        $k->nama = $nama;
        $k->jabatan = $jabatan;
        $k->alamat = $alamat;
        $k->no_telp = $no_telp;

        if ($k->save()) return "success";
        return "error: Gagal memperbarui data";
    }

    public function destroy(Request $request)
    {
        $id = (int) $request->id;
        if ($id <= 0) return "error: ID karyawan tidak valid";

        $k = Karyawan::find($id);
        if (!$k) return "error: Karyawan tidak ditemukan";

        if ($k->delete()) return "success";
        return "error: Gagal menghapus data";
    }
}
