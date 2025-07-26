@extends('layouts.app')

@section('title')
    Edit Room
@endsection

@section('content')
    <div class="page-heading">
        <h3>Edit Room</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('room.index') }}" class="btn btn-primary mb-4">
                    Back
                </a>

                <form action="{{ route('room.update', $room->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="basicInput">Room Name/Number</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Name or Number" value="{{ $room->name }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Room Class</label>
                                        <input type="text" class="form-control" name="class" placeholder="Class"
                                            value="{{ $room->class }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Facility</label>
                                        <textarea class="form-control" name="facility" rows="3">{{ $room->facility }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Price</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="rupiahInput" name="price" class="form-control"
                                                placeholder="Price" value="{{ number_format($room->price, 0, ',', '.') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" class="form-control" name="photo" id="photo"
                                            accept="image/*">

                                        <!-- Preview Foto -->
                                        <div class="mt-2">
                                            <img id="photo-preview"
                                                src="{{ $room->photo ? asset('storage/' . $room->photo) : '#' }}"
                                                alt="Preview Foto"
                                                style="max-width: 200px; {{ $room->photo ? '' : 'display: none;' }} border: 1px solid #ddd; padding: 5px;">
                                        </div>

                                        <!-- Tombol Reset dan Hapus -->
                                        <button type="button" id="btn-reset-photo" class="btn btn-secondary btn-sm mt-2"
                                            style="display: {{ $room->photo ? 'inline-block' : 'none' }};">Reset
                                            Foto</button>
                                        <button type="button" id="btn-remove-photo"
                                            class="btn btn-danger btn-sm mt-2">Delete Foto</button>

                                        <!-- Penanda jika user ingin menghapus foto -->
                                        <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group float-end">
                                <button type="submit" class="btn btn-outline-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const rupiahInput = document.getElementById("rupiahInput");

        rupiahInput.addEventListener("input", function(e) {
            let value = this.value.replace(/[^0-9]/g, ""); // Hapus semua selain angka
            if (value) {
                this.value = formatRupiah(value);
            } else {
                this.value = "";
            }
        });

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>

    <script>
        const inputPhoto = document.getElementById('photo');
        const preview = document.getElementById('photo-preview');
        const btnRemove = document.getElementById('btn-remove-photo');
        const removePhotoFlag = document.getElementById('remove_photo');
        const btnReset = document.getElementById('btn-reset-photo'); // Tombol reset (jika ada)

        // Simpan src asli (foto lama)
        const originalSrc = preview.getAttribute('src');

        // Saat memilih foto baru
        inputPhoto.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    btnRemove.style.display = 'inline-block';
                    if (btnReset) btnReset.style.display = 'inline-block';
                    removePhotoFlag.value = 0; // Tidak hapus foto
                };
                reader.readAsDataURL(file);
            }
        });

        // Saat klik tombol hapus foto
        btnRemove.addEventListener('click', function() {
            inputPhoto.value = ''; // Kosongkan input file
            preview.src = '#'; // Kosongkan gambar
            preview.style.display = 'none'; // Sembunyikan preview
            btnRemove.style.display = 'none'; // Sembunyikan tombol
            if (btnReset) btnReset.style.display = 'none';
            removePhotoFlag.value = 1; // Tandai ingin hapus foto
        });

        // Saat klik tombol reset ke foto lama (hanya muncul di form edit)
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                inputPhoto.value = ''; // Kosongkan input file baru
                preview.src = originalSrc; // Kembalikan ke foto lama
                preview.style.display = 'block';
                btnRemove.style.display = 'inline-block';
                btnReset.style.display = 'inline-block';
                removePhotoFlag.value = 0; // Jangan hapus foto
            });
        }
    </script>
@endpush
