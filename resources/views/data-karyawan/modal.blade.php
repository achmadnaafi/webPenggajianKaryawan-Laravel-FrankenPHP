<!-- Modal Tambah Data -->
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-xl shadow-xl">
    <div class="bg-green-600 text-white text-center py-3 rounded-t-xl text-lg font-semibold shadow-md shadow-green-300">Tambah Data Karyawan</div>
    <form id="formTambah" class="p-6 space-y-4 flex flex-col">
        @csrf
        <div>
            <label class="block font-semibold mb-1 text-black">Nama
                <input type="text" name="nama" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">Jabatan
                <input type="text" name="jabatan" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">Alamat
                <input type="text" name="alamat" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">No Telp
                <input type="text" name="no_telp" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div class="flex justify-end space-x-3 mt-2">
            <button type="button" onclick="closeModalTambah()" class="px-4 py-2 bg-black text-white rounded-full">Kembali</button>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Simpan</button>
        </div>
    </form>
  </div>
</div>

<!-- Modal Edit Data -->
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
    <div class="bg-green-600 text-white text-center py-3 text-lg font-semibold shadow-md">Edit Data Karyawan</div>
    <form id="formEdit" class="p-6 space-y-4 flex flex-col">
        <input type="hidden" name="ftoken" id="edit_ftoken">
        @csrf
        <div>
            <label class="block font-semibold mb-1 text-black">Nama
                <input type="text" name="nama" id="edit_nama" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">Jabatan
                <input type="text" name="jabatan" id="edit_jabatan" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">Alamat
                <input type="text" name="alamat" id="edit_alamat" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div>
            <label class="block font-semibold mb-1 text-black">No Telp
                <input type="text" name="no_telp" id="edit_no_telp" class="border border-green-400 rounded w-full px-3 py-2" required>
            </label>
        </div>
        <div class="flex justify-end space-x-3 mt-2">
            <button type="button" onclick="closeModalEdit()" class="px-4 py-2 bg-black text-white rounded-full">Kembali</button>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-full">Update</button>
        </div>
    </form>
  </div>
</div>
