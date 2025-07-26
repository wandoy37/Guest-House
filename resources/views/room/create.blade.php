@extends('layouts.app')

@section('title')
    Create New Room
@endsection

@section('content')
    <div class="page-heading">
        <h3>Create New Room</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('room.index') }}" class="btn btn-primary mb-4">
                    Back
                </a>

                <form action="{{ route('room.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="basicInput">Room Name/Number</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Name or Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Room Class</label>
                                        <input type="text" class="form-control" name="class" placeholder="Class">
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Facility</label>
                                        <textarea class="form-control" name="facility" rows="3"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Price</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="rupiahInput" name="price" class="form-control"
                                                placeholder="Price">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" class="form-control" name="photo" id="photo"
                                            accept="image/*">

                                        <!-- Preview -->
                                        <div class="mt-2">
                                            <img id="photo-preview" src="#" alt="Preview Foto"
                                                style="max-width: 200px; display: none; border: 1px solid #ddd; padding: 5px;">
                                        </div>

                                        <!-- Tombol Hapus -->
                                        <button type="button" id="btn-remove-photo" class="btn btn-danger btn-sm mt-2"
                                            style="display: none;">Delete</button>

                                        <!-- Optional: Hidden input jika ingin menandai penghapusan foto lama -->
                                        <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group float-end">
                                <button type="submit" class="btn btn-outline-primary">
                                    Save
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

        inputPhoto.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    btnRemove.style.display = 'inline-block';
                    removePhotoFlag.value = 0; // Reset flag
                };
                reader.readAsDataURL(file);
            }
        });

        btnRemove.addEventListener('click', function() {
            inputPhoto.value = ''; // Kosongkan input file
            preview.src = '#'; // Kosongkan gambar
            preview.style.display = 'none'; // Sembunyikan preview
            btnRemove.style.display = 'none'; // Sembunyikan tombol
            removePhotoFlag.value = 1; // Tandai ingin hapus foto
        });
    </script>
@endpush
