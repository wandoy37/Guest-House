@extends('layouts.app')

@section('title')
    Booking Information - {{ $booking->id }}
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Booking Information</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <hr>
                <div class="row py-2">

                    <div class="col-md-12">
                        <a href="{{ route('booking.index') }}" class="btn btn-primary mb-4">
                            Back
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $booking->room->class }}</h4>
                                    <p class="card-text">Room Number - {{ $booking->room->name }}</p>
                                    <p class="card-text">Fasilitas : {{ $booking->room->facility }}</p>
                                </div>
                                <img class="img-fluid w-100" src="{{ asset('storage/' . $booking->room->photo) }}"
                                    alt="Card image cap">
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <h2 class="fst-italic">{{ 'Rp. ' . number_format($booking->room->price, 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="fw-bold">Data Booking</h4>
                                    <input type="text" name="booking_id" value="{{ $booking->id }}" hidden>
                                    <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="room_id">Room</label>
                                        <select id="room_id" class="form-control @error('room_id') is-invalid @enderror"
                                            name="room_id" required>
                                            <option value="">-- Select room --</option>
                                            @foreach ($rooms as $room)
                                                <option value="{{ $room->id }}" data-price="{{ $room->price }}"
                                                    {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                                    {{ $room->name }} - {{ $room->class }} (Rp.
                                                    {{ number_format($room->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('room_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Data Guest</label>
                                        <select class="form-select select2 @error('guest_id') is-invalid @enderror"
                                            name="guest_id">
                                            <option value="">-- Select --</option>
                                            @foreach ($guests as $guest)
                                                <option value="{{ $guest->id }}" @selected(old('guest_id', $booking->guest_id) == $guest->id)>
                                                    {{ $guest->name }} - {{ $guest->identity_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('guest_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row py-2">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Check-In</label>
                                                <input type="date" id="checkin"
                                                    class="form-control @error('checkin') is-invalid @enderror"
                                                    name="checkin" value="{{ old('checkin', $booking->checkin_format) }}">
                                                @error('checkin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Check-Out</label>
                                                <input type="date" id="checkout"
                                                    class="form-control @error('checkout') is-invalid @enderror"
                                                    name="checkout"
                                                    value="{{ old('checkout', $booking->checkout_format) }}">
                                                @error('checkout')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Number of days</label>
                                                <input type="number" id="jumlahHari"
                                                    class="form-control @error('day') is-invalid @enderror" name="day"
                                                    placeholder="Number of days" value="{{ old('day', $booking->day) }}"
                                                    readonly>
                                                @error('day')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Room Charge</label>
                                        <input type="hidden" id="roomPrice" value="{{ $booking->room->price }}">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="roomCharge" class="form-control" name="room_charge"
                                                placeholder="Room Charge"
                                                value="{{ old('room_charge', $booking->room_charge) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Deposit</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="rupiahInput"
                                                class="form-control @error('deposit') is-invalid @enderror" name="deposit"
                                                placeholder="Deposit" value="{{ old('deposit', $booking->deposit) }}">
                                        </div>
                                        @error('deposit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Total Payment</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text"
                                                class="form-control @error('total_payment') is-invalid @enderror"
                                                name="total_payment" placeholder="Total payment"
                                                value="{{ old('total_payment', $booking->total_payment) }}" readonly>
                                        </div>
                                        @error('total_payment')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group my-4">
                                        <label>Action <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="action" value="update"
                                                id="update" required>
                                            <label class="form-check-label" for="update">
                                                Update
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="action" value="cancel"
                                                id="cancel">
                                            <label class="form-check-label" for="cancel">
                                                Cancel
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group my-4">
                                        <label>Note <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('note') is-invalid @enderror" name="note" rows="2" required>{{ old('note') }}</textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="float-end">
                                        <button type="submit" class="btn btn-outline-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Booking Log --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>History</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Receptionist</th>
                                                <th>Action</th>
                                                <th>Note</th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->logs as $log)
                                                <tr>
                                                    <td>{{ $log->user->username }}</td>
                                                    <td>{{ $log->action }}</td>
                                                    <td>{{ $log->description }}</td>
                                                    <td>{{ $log->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('style')
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet" />
@endpush
@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $(document).ready(function() {
            $('.select2-room').select2();
        });
    </script>

    <script>
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseRupiah(str) {
            return parseInt(str.replace(/\./g, '')) || 0;
        }

        function hitungHariDanTotal() {
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const dayInput = document.getElementById('jumlahHari');
            const roomChargeInput = document.getElementById('roomCharge');
            const roomPrice = parseFloat(document.getElementById('roomPrice').value);

            let selisih = 0;

            if (checkin && checkout) {
                const tglCheckin = new Date(checkin);
                const tglCheckout = new Date(checkout);
                selisih = (tglCheckout - tglCheckin) / (1000 * 60 * 60 * 24);

                if (selisih >= 0) {
                    dayInput.value = selisih;
                    const totalRoomCharge = roomPrice * selisih;
                    roomChargeInput.value = formatRupiah(totalRoomCharge);
                } else {
                    dayInput.value = 0;
                    roomChargeInput.value = "0";
                }
            } else {
                dayInput.value = '';
                roomChargeInput.value = '';
            }

            hitungTotalPayment(); // hitung ulang total setelah update roomCharge
        }

        function hitungTotalPayment() {
            const roomCharge = parseRupiah(document.getElementById('roomCharge').value);
            const deposit = parseRupiah(document.getElementById('rupiahInput').value);
            const total = roomCharge + deposit;

            document.querySelector('input[name="total_payment"]').value = formatRupiah(total);
        }

        // NEW: Function untuk update harga room berdasarkan pilihan
        function updateRoomPrice() {
            const roomSelect = document.getElementById('room_id'); // Sesuaikan dengan ID select room
            const roomPriceInput = document.getElementById('roomPrice');
            const roomChargeInput = document.getElementById('roomCharge');

            if (roomSelect && roomSelect.value) {
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                const roomPrice = selectedOption.getAttribute('data-price');

                if (roomPrice) {
                    // Update hidden input room price
                    roomPriceInput.value = roomPrice;

                    // Reset room charge dan hitung ulang
                    roomChargeInput.value = '';
                    hitungHariDanTotal();
                }
            }
        }

        // Format input deposit saat diketik
        document.getElementById("rupiahInput").addEventListener("input", function(e) {
            let value = this.value.replace(/[^0-9]/g, "");
            this.value = value ? formatRupiah(value) : "";
            hitungTotalPayment(); // update total setiap deposit berubah
        });

        // Event listener perubahan tanggal
        document.getElementById('checkin').addEventListener('change', hitungHariDanTotal);
        document.getElementById('checkout').addEventListener('change', hitungHariDanTotal);

        // NEW: Event listener untuk perubahan room selection
        document.addEventListener('DOMContentLoaded', function() {
            const roomSelect = document.getElementById('room_id');
            if (roomSelect) {
                roomSelect.addEventListener('change', updateRoomPrice);
            }
        });

        // Format nilai awal saat halaman dimuat
        function formatInitialValues() {
            const depositInput = document.getElementById('rupiahInput');
            const totalPaymentInput = document.querySelector('input[name="total_payment"]');
            const roomChargeInput = document.getElementById('roomCharge');

            // Format deposit value
            if (depositInput.value && depositInput.value !== '') {
                const depositValue = depositInput.value.replace(/[^0-9]/g, "");
                if (depositValue) {
                    depositInput.value = formatRupiah(depositValue);
                }
            }

            // Format total payment value
            if (totalPaymentInput.value && totalPaymentInput.value !== '') {
                const totalValue = totalPaymentInput.value.replace(/[^0-9]/g, "");
                if (totalValue) {
                    totalPaymentInput.value = formatRupiah(totalValue);
                }
            }

            // Format room charge value
            if (roomChargeInput.value && roomChargeInput.value !== '') {
                const roomChargeValue = roomChargeInput.value.replace(/[^0-9]/g, "");
                if (roomChargeValue) {
                    roomChargeInput.value = formatRupiah(roomChargeValue);
                }
            }
        }

        // Jalankan saat halaman dimuat
        window.addEventListener('DOMContentLoaded', () => {
            formatInitialValues(); // Format nilai awal terlebih dahulu
            hitungHariDanTotal();
            hitungTotalPayment();
        });
    </script>
@endpush
