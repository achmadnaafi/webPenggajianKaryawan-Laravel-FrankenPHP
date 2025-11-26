<!-- Modal Tambah -->
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold">Tambah Data Gaji</div>
    <form id="formTambah" method="POST" class="p-6 space-y-4">
      @csrf
      <div>
        <label for="id_karyawan" class="block font-semibold mb-1">Nama Karyawan</label>
        <select id="id_karyawan" name="id_karyawan" class="border border-gray-400 rounded w-full px-3 py-2" required onchange="isiOtomatisTambah()">
          <option value="">-- Pilih Karyawan --</option>
          @foreach($karyawan as $k)
            <option value="{{ $k->id }}" data-no="{{ $k->no_telp }}">{{ $k->nama }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block font-semibold mb-1">No. Telepon</label>
        <input id="no_telp_tambah" type="text" class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100" readonly placeholder="No Telepon otomatis terisi">
      </div>

      <div class="flex gap-2">
        <div class="flex-1">
          <label for="bulan" class="block font-semibold mb-1">Bulan</label>
          <select id="bulan" name="bulan" required class="border border-gray-400 rounded w-full px-3 py-2">
            @foreach($bulan_list as $b) <option value="{{ $b }}">{{ $b }}</option> @endforeach
          </select>
        </div>
        <div class="flex-1">
          <label for="tahun" class="block font-semibold mb-1">Tahun</label>
          <input id="tahun" name="tahun" type="number" min="2000" max="2100" value="{{ date('Y') }}" class="border border-gray-400 rounded w-full px-3 py-2" required>
        </div>
      </div>

      <div>
        <label for="gaji_pokok" class="block font-semibold mb-1">Gaji Pokok</label>
        <input id="gaji_pokok" name="gaji_pokok" type="number" min="0" step="0.01" value="0" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div>
        <label for="tunjangan" class="block font-semibold mb-1">Tunjangan</label>
        <input id="tunjangan" name="tunjangan" type="number" min="0" step="0.01" value="0" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div>
        <label for="potongan" class="block font-semibold mb-1">Potongan</label>
        <input id="potongan" name="potongan" type="number" min="0" step="0.01" value="0" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div class="flex justify-end space-x-3 mt-4">
        <button type="button" onclick="closeModalTambah()" class="bg-black text-white px-4 py-2 rounded-full">Kembali</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-lg shadow-lg overflow-hidden">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold">Edit Data Gaji</div>
    <form id="formEdit" method="POST" class="p-6 space-y-4">
      @csrf
      <input type="hidden" name="id_gaji" id="edit_id_gaji">
      <input type="hidden" name="id_karyawan" id="hidden_id_karyawan">

      <div>
        <label class="block font-semibold mb-1">Nama Karyawan</label>
        <input id="edit_nama_karyawan" type="text" class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100" readonly>
      </div>

      <div>
        <label class="block font-semibold mb-1">No. Telepon</label>
        <input id="no_telp_edit" type="text" class="border border-gray-400 rounded w-full px-3 py-2 bg-gray-100" readonly>
      </div>

      <div class="flex gap-2">
        <div class="flex-1">
          <label for="edit_bulan" class="block font-semibold mb-1">Bulan</label>
          <select id="edit_bulan" name="bulan" required class="border border-gray-400 rounded w-full px-3 py-2">
            @foreach($bulan_list as $b) <option value="{{ $b }}">{{ $b }}</option> @endforeach
          </select>
        </div>
        <div class="flex-1">
          <label for="edit_tahun" class="block font-semibold mb-1">Tahun</label>
          <input id="edit_tahun" name="tahun" type="number" min="2000" max="2100" value="{{ date('Y') }}" class="border border-gray-400 rounded w-full px-3 py-2" required>
        </div>
      </div>

      <div>
        <label for="edit_gaji_pokok" class="block font-semibold mb-1">Gaji Pokok</label>
        <input id="edit_gaji_pokok" name="gaji_pokok" type="number" min="0" step="0.01" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div>
        <label for="edit_tunjangan" class="block font-semibold mb-1">Tunjangan</label>
        <input id="edit_tunjangan" name="tunjangan" type="number" min="0" step="0.01" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div>
        <label for="edit_potongan" class="block font-semibold mb-1">Potongan</label>
        <input id="edit_potongan" name="potongan" type="number" min="0" step="0.01" class="border border-gray-400 rounded w-full px-3 py-2" required>
      </div>

      <div class="flex justify-end space-x-3 mt-4">
        <button type="button" onclick="closeModalEdit()" class="bg-black text-white px-4 py-2 rounded-full">Kembali</button>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Update</button>
      </div>
    </form>
  </div>
</div>
