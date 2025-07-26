@extends('layouts.app')

@section('title')
    Reservation
@endsection

@section('content')
    <div class="page-heading">
        <h3>Reservation</h3>
    </div>
    <div class="page-content">
        <div class="row py-2">
            <div class="col-md-12">
                <a href="{{ route('check.in') }}" class="btn btn-primary mb-4">
                    Back
                </a>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title">{{ $room->class }}</h4>
                            <p class="card-text">Room Number - {{ $room->name }}</p>
                            <p class="card-text">Fasilitas : {{ $room->facility }}</p>
                        </div>
                        <img class="img-fluid w-100" src="{{ asset('storage/' . $room->photo) }}" alt="Card image cap">
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        <h2 class="fst-italic">{{ 'Rp. ' . number_format($room->price, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form action="{{ route('check.in.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="fw-bold">Data Booking</h4>
                            <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Data Guest</label>
                                <input type="text" name="status" value="checkin" hidden>
                                <input type="text" name="room_id" value="{{ $room->id }}" hidden>
                                <select class="form-select select2 @error('guest_id') is-invalid @enderror" name="guest_id">
                                    <option value="">-- Select --</option>
                                    @foreach ($guests as $guest)
                                        <option value="{{ $guest->id }}" @selected(old('guest_id') == $guest->id)>
                                            {{ $guest->name }} - {{ $guest->identity_number }}
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
                                            class="form-control @error('checkin') is-invalid @enderror" name="checkin"
                                            value="{{ old('checkin') }}">
                                        @error('checkin')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Check-Out</label>
                                        <input type="date" id="checkout"
                                            class="form-control @error('checkout') is-invalid @enderror" name="checkout"
                                            value="{{ old('checkout') }}">
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
                                            placeholder="Number of days" value="{{ old('day') }}" readonly>
                                        @error('day')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Room Charge</label>
                                <input type="hidden" id="roomPrice" value="{{ $room->price }}">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="roomCharge" class="form-control" name="room_charge"
                                        placeholder="Room Charge" value="{{ old('room_charge') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Deposit</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="rupiahInput"
                                        class="form-control @error('deposit') is-invalid @enderror" name="deposit"
                                        placeholder="Deposit" value="{{ old('deposit', '0') }}">
                                </div>
                                @error('deposit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Total Payment</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control @error('total_payment') is-invalid @enderror"
                                        name="total_payment" placeholder="total_payment" readonly>
                                </div>
                                @error('total_payment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-end">
                                <button type="submit" class="btn btn-outline-primary">Check-In</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

        // Format input deposit saat diketik
        document.getElementById("rupiahInput").addEventListener("input", function(e) {
            let value = this.value.replace(/[^0-9]/g, "");
            this.value = value ? formatRupiah(value) : "";
            hitungTotalPayment(); // update total setiap deposit berubah
        });

        // Event listener perubahan tanggal
        document.getElementById('checkin').addEventListener('change', hitungHariDanTotal);
        document.getElementById('checkout').addEventListener('change', hitungHariDanTotal);

        // Jalankan awal saat halaman dibuka
        window.addEventListener('DOMContentLoaded', () => {
            hitungHariDanTotal();
            hitungTotalPayment();
        });
    </script>
@endpush
