@extends('layouts.app')

@section('title')
    Revenue Report
@endsection

@section('content')
    <div class="page-heading">
        <h3>Revenue Report</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Monthly Revenue
                            <span>Rp.{{ number_format($monthly['total_bulanan'], 0, ',', '.') }}</span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <canvas id="weeklyChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets') }}/extensions/chart.js/chart.umd.js"></script>
    {{-- <script src="{{ asset('assets') }}/static/js/pages/ui-chartjs.js"></script> --}}

    <script>
        var weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        var weeklyChart = new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weeklyLabels) !!},
                datasets: [{
                    label: 'Weekly Revenue',
                    data: {!! json_encode($weeklyValues) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
