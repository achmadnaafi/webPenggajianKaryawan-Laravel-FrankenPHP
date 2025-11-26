@extends('layouts.app')

@section('title', 'Kelola Data Karyawan')

@section('content')
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6">
    <a href="{{ url('/') }}" class="w-full sm:w-auto text-center bg-black text-white px-5 py-2 font-semibold hover:bg-gray-800 transition rounded-sm">Kembali</a>
    <a href="{{ url('data-karyawan') }}" class="w-full sm:w-auto text-center bg-white text-green-600 border border-green-600 px-5 py-2 font-semibold hover:bg-green-50 transition rounded-sm">Data Karyawan</a>
</div>

<div class="bg-white shadow-lg w-full p-6 rounded-md">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-lg font-bold">Kelola Gaji Karyawan</h2>
    <button onclick="openModalTambah()" class="bg-green-600 text-white px-4 py-2 rounded-full">Tambah</button>
  </div>

  <div class="overflow-x-auto">
      <table class="min-w-full border-collapse text-left text-sm">
        <thead>
          <tr class="bg-gray-100 text-gray-700 border-b font-bold">
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Nama</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">No. Telp</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Periode</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Gaji Pokok</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Tunjangan</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Potongan</th>
            <th class="py-2 px-2 w-1/6 whitespace-nowrap truncate">Total Gaji</th>
            <th class="py-2 px-2 w-1/12 text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($gaji as $g)
        <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-2 whitespace-nowrap truncate">{{ $g->karyawan->nama ?? '-' }}</td>
            <td class="py-2 px-2 whitespace-nowrap truncate">{{ $g->karyawan->no_telp ?? '-' }}</td>
            <td class="py-2 px-2 whitespace-nowrap">{{ $g->bulan }} {{ $g->tahun }}</td>
            <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format($g->gaji_pokok,2,',','.') }}</td>
            <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format($g->tunjangan,2,',','.') }}</td>
            <td class="py-2 px-2 whitespace-nowrap">Rp. {{ number_format($g->potongan,2,',','.') }}</td>
            <td class="py-2 px-2 whitespace-nowrap font-semibold">Rp. {{ number_format($g->total_gaji,2,',','.') }}</td>
            <td class="py-2 px-2 text-center whitespace-nowrap">
                <button
                    class="text-green-600 hover:text-green-800 mx-1 open-edit"
                    data-id="{{ $g->id_gaji }}"
                    data-id_karyawan="{{ $g->id_karyawan }}"
                    data-bulan="{{ $g->bulan }}"
                    data-tahun="{{ $g->tahun }}"
                    data-gaji_pokok="{{ $g->gaji_pokok }}"
                    data-tunjangan="{{ $g->tunjangan }}"
                    data-potongan="{{ $g->potongan }}">
                    <i class="ri-edit-2-fill text-xl"></i>
                </button>

                <button class="text-red-600 hover:text-red-800 mx-1 btn-delete" data-id="{{ $g->id_gaji }}">
                    <i class="ri-delete-bin-5-fill text-xl"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  </div>
</div>

@include('gaji-karyawan.modal')
@endsection

@section('scripts')
<script>
const CSRF_TOKEN = "{{ csrf_token() }}";
const MAX_VALUE = 9999999999.99;

// Validasi maksimal gaji sebelum submit
function validasiGaji(form){
    const gaji_pokok = parseFloat(form.querySelector('[name="gaji_pokok"]').value) || 0;
    const tunjangan = parseFloat(form.querySelector('[name="tunjangan"]').value) || 0;
    const potongan = parseFloat(form.querySelector('[name="potongan"]').value) || 0;
    const total_gaji = Math.max(0, gaji_pokok + tunjangan - potongan);

    if(gaji_pokok > MAX_VALUE){
        alert("Gaji pokok terlalu besar, maksimal 9.999.999.999,99");
        return false;
    }
    if(tunjangan > MAX_VALUE){
        alert("Tunjangan terlalu besar, maksimal 9.999.999.999,99");
        return false;
    }
    if(potongan > MAX_VALUE){
        alert("Potongan terlalu besar, maksimal 9.999.999.999,99");
        return false;
    }
    if(total_gaji > MAX_VALUE){
        alert("Total gaji melebihi batas maksimal 9.999.999.999,99");
        return false;
    }
    return true;
}

// AUTO-FILL NO TELP UNTUK ADD GAJI
function isiOtomatisTambah(){
  const select = document.getElementById('id_karyawan');
  document.getElementById('no_telp_tambah').value = select.options[select.selectedIndex]?.dataset.no || '';
}

function openModalTambah(){ document.getElementById('modalTambah').classList.remove('hidden'); }
function closeModalTambah(){ document.getElementById('modalTambah').classList.add('hidden'); }

function openModalEditFromBtn(btn){
  const id = btn.dataset.id;
  document.getElementById('modalEdit').classList.remove('hidden');
  document.getElementById('edit_id_gaji').value = id;
  document.getElementById('hidden_id_karyawan').value = btn.dataset.id_karyawan || '';
  document.getElementById('edit_nama_karyawan').value = (btn.closest('tr').querySelector('td')?.innerText || '');
  document.getElementById('no_telp_edit').value = (btn.closest('tr').children[1]?.innerText || '');
  document.getElementById('edit_bulan').value = btn.dataset.bulan;
  document.getElementById('edit_tahun').value = btn.dataset.tahun;
  document.getElementById('edit_gaji_pokok').value = btn.dataset.gaji_pokok;
  document.getElementById('edit_tunjangan').value = btn.dataset.tunjangan;
  document.getElementById('edit_potongan').value = btn.dataset.potongan;
}

function closeModalEdit(){ document.getElementById('modalEdit').classList.add('hidden'); }

document.addEventListener('click', function(e){
  if(e.target.closest('.open-edit')){
    openModalEditFromBtn(e.target.closest('.open-edit'));
  }
  if(e.target.closest('.btn-delete')){
    const id = e.target.closest('.btn-delete').dataset.id;
    hapusData(id);
  }
});

// HAPUS
function hapusData(id_gaji){
  if(!confirm("Apakah Anda yakin ingin menghapus gaji ini?")) return;
  fetch("{{ route('gaji.destroy') }}",{
    method:'POST',
    headers:{ 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    body: new URLSearchParams({ id_gaji })
  })
  .then(res => res.text())
  .then(r => { if(r.includes('success')){ alert('Data berhasil dihapus!'); location.reload(); } else alert('Gagal hapus: '+r); })
  .catch(err=>alert('Error: '+err));
}

// SUBMIT TAMBAH
document.getElementById('formTambah').addEventListener('submit', function(e){
  e.preventDefault();
  if(!validasiGaji(this)) return;
  const formData = new FormData(this);
  fetch("{{ route('gaji.store') }}",{
    method:'POST',
    headers:{ 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    body: formData
  })
  .then(res => res.text())
  .then(r => { if(r.includes('success')){ alert('Data berhasil ditambahkan!'); closeModalTambah(); location.reload(); } else alert('Gagal: '+r); })
  .catch(err=>alert('Error: '+err));
});

// SUBMIT EDIT
document.getElementById('formEdit').addEventListener('submit', function(e){
  e.preventDefault();
  if(!validasiGaji(this)) return;
  const formData = new FormData(this);
  fetch("{{ route('gaji.update') }}",{
    method:'POST',
    headers:{ 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    body: formData
  })
  .then(res => res.text())
  .then(r => { if(r.includes('success')){ alert('Data berhasil diperbarui!'); closeModalEdit(); location.reload(); } else alert('Gagal: '+r); })
  .catch(err=>alert('Error: '+err));
});
</script>
@endsection
