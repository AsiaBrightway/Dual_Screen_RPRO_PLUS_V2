@extends('layouts.admin.master')
@section('title', 'Shops Reports')

@section('content')
    <style>
        /* Card Hover Effect */
        .report-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #eef2f7;
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border-color: #0d6efd; /* Highlight border on hover */
        }

        .report-card.clickable {
            cursor: pointer;
        }

        /* Icon box styling */
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">MultiShop Reports</span>
        </div>
        <div class="home-content" style="margin-left: 20px">
            <div class="table_buttons_container mb-3" style="display: flex; justify-content:end; gap:5px; margin-right:11px">
                <form method="GET" action="{{ route('reports#shopsReport') }}">
                    <input type="date" class="form-control"  name="date" 
                        value="{{ request()->query('date', now()->format('Y-m-d')) }}"
                        onchange="this.form.submit()">
                </form>
            </div>

            {{-- <div class="stock_out_report_container row">
                <div class="sale-report-right">
                    <div class="p-3" style="background-color: #fff; height: 100%; border-radius: 10px; overflow-y: auto;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h1 style="font-size: 22px; font-weight: 500;">Daily Shop Reports</h1>
                            <div>
                                {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                            </div>
                        </div>

                    </div>
                </div>

            </div> --}}
            <div class="stock_out_report_container row">
                <div class="sale-report-right">
                    <div class="p-4" style="background-color: #f8f9fa; height: 100%; border-radius: 10px; overflow-y: auto;">
                        
                        {{-- Header Section --}}
                        <div class="d-flex align-items-center justify-content-between mb-4">
                                <h1 class="mb-0" style="font-size: 24px; font-weight: 700; color: #333;">Daily Shop Reports</h1>
                                <p class="text-muted mb-0" style="font-size: 14px;">Overview for {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</p>
                        </div>
                        
                        {{-- Cards Container --}}
                        <div class="row g-4">
                            @forelse($reports as $report)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 report-card">
                                        <div class="card-body"> 
                                            {{-- Card Header: Shop Name & Status --}}
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h5 class="card-title fw-bold text-dark mb-1">
                                                        {{ $report['name'] ?? 'Shop Name' }}
                                                    </h5>
                                                </div>
                                                @php
                                                    $statusColor = ($report['sales_count'] > 0) ? 'success' : 'secondary';
                                                    $statusText = ($report['sales_count'] > 0) ? 'Active' : 'No Sales';
                                                @endphp
                                                <span class="badge bg-light text-{{ $statusColor }} border border-{{ $statusColor }}">
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <hr class="my-3 text-muted" style="opacity: 0.1;">

                                            {{-- Metrics Section --}}
                                            <div class="row mb-4">
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-2">
                                                            $
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Sales Amount</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ number_format($report['sales_amount'] ?? 0) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-2">
                                                            <i class="fa-solid fa-cart-shopping"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Purchases Amount</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ number_format($report['purchases_amount'] ?? 0) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-2">
                                                            %
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Sales Discount</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ $report['sales_discount'] ?? 0 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-2">
                                                            %
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Purchases Discount</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ $report['purchases_discount'] ?? 0 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-primary bg-opacity-10 text-primary me-2">
                                                            <i class="fa-solid fa-calculator"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Total Sales</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ $report['sales_count'] ?? 0 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-2">
                                                            <i class="fa-solid fa-calculator"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block" style="font-size: 11px;">Total Purchases</small>
                                                            <span class="fw-bold text-dark">
                                                                {{ $report['purchases_count'] ?? 0 }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Stretched link to make entire card clickable --}}
                                            {{-- <a href="{{ $report['link'] ?? '#' }}" class="stretched-link" target="_blank" rel="noopener noreferrer"></a> --}}
                                            @if(isset($report['link']))
                                                <a href="{{ $report['link'] }}?date={{ $date }}" class="stretched-link" target="_blank" rel="noopener noreferrer"></a>
                                            @endif
                                        </div>       
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center" role="alert">
                                        No reports found for this date.
                                    </div>
                                </div>
                            @endforelse

                        </div>
                        {{-- End Cards --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection