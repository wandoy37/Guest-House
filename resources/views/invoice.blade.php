<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran - {{ $invoice_number }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        @media print {
            body {
                margin: 0;
                box-shadow: none;
            }

            .invoice-box {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            background: #fff;
            margin: 0;
        }

        .invoice-box {
            max-width: 210mm;
            padding: 20mm;
            margin: auto;
            background: #fff;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
        }

        .paid-stamp {
            font-size: 24px;
            font-weight: bold;
            color: green;
            border: 2px solid green;
            padding: 5px 15px;
            border-radius: 5px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        @media print {
            .row-print {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
            }

            .row-print>div {
                width: 48%;
            }
        }

        .divider {
            height: 2px;
            background-color: #000;
            /* warna hitam */
            margin: 20px 0;
        }

        .divider-primary {
            height: 1px;
            background-color: #25396f;
            /* warna Bootstrap primary */
            margin: 20px 0;
        }
    </style>
</head>

<body>

    <div class="container invoice-box">
        <div class="invoice-header mb-3">
            <div>
                <h3>{{ $guesthouse['name'] }}</h3>
                <p>
                    {{ $guesthouse['address'] }} <br>
                    Email: {{ $guesthouse['email'] }} <br>
                    WA / Telp: {{ $guesthouse['phone'] }}
                </p>
            </div>
            <div class="text-end">
                <h4>INVOICE</h4>
                <p>No. Faktur: <strong>{{ $invoice_number }}</strong></p>
                <span class="paid-stamp">LUNAS</span>
            </div>

        </div>

        <div class="divider-primary"></div>

        <div class="row row-print mb-3">
            <div class="col-md-6">
                <h5>Data Tamu</h5>
                <p>
                    Nama: <strong>{{ $guest['name'] }}</strong><br>
                    Alamat: {{ $guest['address'] }}<br>
                    Email: {{ $guest['email'] }}<br>
                    No. HP: {{ $guest['phone'] }}
                </p>
            </div>
            <div class="col-md-6">
                <h5>Rincian Booking</h5>
                <p>
                    Check-in: <strong>{{ $booking['checkin'] }}</strong><br>
                    Check-out: <strong>{{ $booking['checkout'] }}</strong>
                </p>
            </div>
        </div>

        <div class="divider-primary"></div>

        <div class="mb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Hari</th>
                        <th class="text-end">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $room['class'] }}</td>
                        <td>Rp {{ number_format($room['price'], 0, ',', '.') }}</td>
                        <td>{{ $room['days'] }}</td>
                        <td class="text-end">Rp {{ number_format($payment['room_charge'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <h5>Rincian Pembayaran</h5>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Deposit</td>
                        <td class="text-end">Rp {{ number_format($payment['deposit'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Pembayaran</th>
                        <th class="text-end">Rp {{ number_format($payment['total'], 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <p><strong>Tanggal Cetak:</strong> {{ date('d M Y') }}</p>
            <img src="{{ asset('assets/img/qr-placeholder.png') }}" alt="QR Code" width="100">
            <p style="font-size: 12px;">Scan untuk validasi digital</p>
        </div>
    </div>

</body>

</html>
