<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice Pembayaran - {{ $invoice->invoice_number }}</title>
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

            /* Ganti dengan ini untuk mempertahankan proporsi 8:4 */
            .row-print>div.col-md-8 {
                width: 66.666667%;
            }

            .row-print>div.col-md-4 {
                width: 33.333333%;
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
        <div class="invoice-header mb-2">
            <div>
                <h3>Guest House</h3>
                <p>
                    Jl. Lorem ipsum dolor sit amet consectetur.<br>
                    Email: guesthouse@gmail.com <br>
                    WA / Telp: +62 821-4872-2747
                </p>
            </div>
            <div class="text-end">
                <h4>INVOICE</h4>
                <p class="fst-italic">No. Invoice: <strong>#{{ $invoice->invoice_number }}</strong></p>
                <span class="paid-stamp">PAID</span>
            </div>

        </div>

        <div class="divider-primary"></div>

        <div class="row row-print mb-2">
            <div class="col-md-8">
                <h5>Data Guest</h5>
                <p>
                    Nama: <strong>{{ $invoice->guest->name }}</strong><br>
                    Alamat: {{ $invoice->guest->address }}<br>
                    Email: {{ $invoice->guest->email }}<br>
                    No. HP: {{ $invoice->guest->phone }}
                </p>
            </div>
            <div class="col-md-4">
                <strong style="color: #25396f; font-style: italic;">Details Booking</strong>
                <p style="font-style: italic;">
                    Check-in:
                    <span>{{ \Carbon\Carbon::parse($invoice->booking->checkin)->format('d-m-Y') }}</span><br>
                    Check-out:
                    <span>{{ \Carbon\Carbon::parse($invoice->booking->checkout)->format('d-m-Y') }}</span>
                </p>
            </div>
        </div>



        <div class="mb-3">
            <table class="table table-borderless">
                <thead style="border-top: 1px solid #607080; border-bottom: 1px solid #607080;">
                    <tr class="text-center">
                        <th>Room #</th>
                        <th>Unit rice</th>
                        <th>Number of Days</th>
                        <th class="text-end">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid #607080;">
                    <tr>
                        <td>{{ $invoice->booking->room->class }}</td>
                        <td class="text-end">Rp {{ number_format($invoice->booking->room->price, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $invoice->booking->day }}</td>
                        <td class="text-end">Rp {{ number_format($invoice->booking->room_charge, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Deposit</td>
                        <td></td>
                        <td></td>
                        <td class="text-end">Rp {{ number_format($invoice->booking->deposit, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-center">Total Amount</th>
                        <th class="text-end">Rp {{ number_format($invoice->booking->total_payment, 0, ',', '.') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-end pt-4 mt-4">
            <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}
            </p>
            {!! QrCode::size(75)->generate(route('invoice.show', $invoice->invoice_number)) !!}
            <p style="font-size: 12px;" class="mt-4">Scan to validate digital invoice</p>
        </div>
    </div>

</body>

</html>
