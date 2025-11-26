@extends('layouts.app')

@section('title', 'Kelola Data Karyawan')

@section('content')
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6">
    <a href="{{ url('/') }}" class="w-full sm:w-auto text-center bg-black text-white px-5 py-2 font-semibold hover:bg-gray-800 transition rounded-sm">Kembali</a>
    <a href="{{ url('gaji-karyawan/') }}" class="w-full sm:w-auto text-center bg-white text-green-600 border border-green-600 px-5 py-2 font-semibold hover:bg-green-50 transition rounded-sm">Gaji Karyawan</a>
</div>

<div class="bg-white shadow-lg w-full p-8 rounded-md">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h2 class="text-lg font-bold tracking-widest text-gray-800 border-b pb-2">KELOLA DATA KARYAWAN</h2>
        <button onclick="openModalTambah()" class="bg-green-600 text-white px-5 py-2 rounded-full font-semibold hover:bg-green-700 transition">Add Data</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="bg-gray-100 text-gray-700 border-b font-bold">
                    <th class="py-3 px-4 font-semibold">No</th>
                    <th class="py-3 px-4 font-semibold">Nama</th>
                    <th class="py-3 px-4 font-semibold">Jabatan</th>
                    <th class="py-3 px-4 font-semibold">Alamat</th>
                    <th class="py-3 px-4 font-semibold">No Telp</th>
                    <th class="py-3 px-4 text-center font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @foreach($karyawans as $index => $k)
                    @php
                        $token = array_keys(session('edit_tokens', []))[$index];
                    @endphp
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-2 px-3 font-semibold">{{ $index + 1 }}</td>
                    <td class="py-2 px-3">{{ $k->nama }}</td>
                    <td class="py-2 px-3">{{ $k->jabatan }}</td>
                    <td class="py-2 px-3">{{ $k->alamat }}</td>
                    <td class="py-2 px-3">{{ $k->no_telp }}</td>
                    <td class="py-2 px-3 text-center">
                        <button onclick="editData('{{ $k->id }}','{{ $k->nama }}','{{ $k->jabatan }}','{{ $k->alamat }}','{{ $k->no_telp }}','{{ $token }}')" class="text-green-600 hover:text-green-800 mx-1"><i class="ri-edit-2-fill text-xl"></i></button>
                        <button onclick="hapusData('{{ $k->id }}')" class="text-red-600 hover:text-red-800 mx-1"><i class="ri-delete-bin-5-fill text-xl"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('data-karyawan.modal')

@endsection

@section('scripts')
<script>
function openModalTambah() { document.getElementById('modalTambah').classList.remove('hidden'); }
function closeModalTambah() { document.getElementById('modalTambah').classList.add('hidden'); }

function editData(id, nama, jabatan, alamat, no_telp, token){
    document.getElementById('edit_ftoken').value = token;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_jabatan').value = jabatan;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_no_telp').value = no_telp;
    document.getElementById('modalEdit').classList.remove('hidden');
}
function closeModalEdit() { document.getElementById('modalEdit').classList.add('hidden'); }

// ===== Tambah Data =====
document.getElementById('formTambah').addEventListener('submit', function(e){
    e.preventDefault();
    const noTelp = this.querySelector('input[name="no_telp"]').value.trim();
    if(!/^628\d{7,10}$/.test(noTelp)){ alert("Nomor telepon harus diawali dengan 628"); return; }

    fetch("{{ route('karyawan.store') }}", { method:'POST', body:new FormData(this) })
    .then(res=>res.text())
    .then(resp=>{
        if(resp.includes('success')){ alert("Data berhasil ditambahkan!"); closeModalTambah(); location.reload(); }
        else{ alert("Gagal menambah data: "+resp); }
    });
});

// ===== Edit Data =====
document.getElementById('formEdit').addEventListener('submit', function(e){
    e.preventDefault();
    const noTelp = this.querySelector('input[name="no_telp"]').value.trim();
    if(!/^628\d{7,10}$/.test(noTelp)){ alert("Nomor telepon harus diawali dengan 628"); return; }

    fetch("{{ route('karyawan.update') }}", { method:'POST', body:new FormData(this) })
    .then(res=>res.text())
    .then(resp=>{
        if(resp.includes('success')){ alert("Data berhasil diperbarui!"); closeModalEdit(); location.reload(); }
        else{ alert("Gagal memperbarui data: "+resp); }
    });
});

// ===== Hapus Data =====
function hapusData(id){
    if(confirm("Yakin ingin menghapus data ini?")){
        const fd = new FormData();
        fd.append('id', id);
        fd.append('_token', '{{ csrf_token() }}');
        fetch("{{ route('karyawan.destroy') }}", { method:'POST', body:fd })
        .then(res=>res.text())
        .then(resp=>{
            if(resp.includes('success')){ alert("Data berhasil dihapus!"); location.reload(); }
            else{ alert("Gagal menghapus data: "+resp); }
        });
    }
}
</script>
@endsection
