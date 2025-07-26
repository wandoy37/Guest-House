@extends('layouts.app')

@section('title')
    New Guest
@endsection

@section('content')
    <div class="page-heading">
        <h3>New guest</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">

                <a href="{{ route('guest.index') }}" class="btn btn-primary mb-4">
                    Back
                </a>

                <form action="{{ route('guest.store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="basicInput">Identity type</label>
                                        <select class="form-select @error('type') is-invalid @enderror" id="basicSelect"
                                            name="type">
                                            <option value="">-- Select --</option>
                                            <option value="KTP" @selected(old('type') == 'KTP')>KTP</option>
                                            <option value="SIM" @selected(old('type') == 'SIM')>SIM</option>
                                            <option value="PASPOR" @selected(old('type') == 'PASPOR')>PASPOR</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Identity number</label>
                                        <input type="text"
                                            class="form-control @error('identity_number') is-invalid @enderror"
                                            name="identity_number" placeholder="Identity number"
                                            value="{{ old('identity_number') }}">
                                        @error('identity_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="basicInput">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="basicInput">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="basicInput">Email address</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Email address" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="basicInput">Phone number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" placeholder="Phone number" value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
