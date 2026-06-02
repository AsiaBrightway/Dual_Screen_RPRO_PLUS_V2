@extends('layouts.admin.master')
@section('title', 'Stock Ledger')
<link rel="stylesheet" href="{{ asset('css/stock_ledger_style.css') }}">

@section('content')
<style>
    .dataTables_scrollHead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #fff;
        border-bottom: 2px solid #dee2e6;
        scrollbar-gutter: stable;
    }

    .dataTables_scrollHead thead th {
        background-color: #f8f9fa;
        white-space: nowrap;
    }

    .dataTables_scrollBody {
        scrollbar-gutter: stable;
    }

    .negative-stock {
        color: #dc3545;
        font-weight: bold;
    }
</style>
<section class="home-section">
    <div class="home-title">
        <i class='bx bx-menu'></i>
        <span class="text">Stock Ledger</span>
    </div>

    <div class="home-content">
        <div class="table_buttons_container mb-3 d-flex flex-wrap justify-content-start justify-content-sm-end align-items-start gap-2" style="margin-right:11px">
            <div class="d-flex flex-column flex-md-row gap-2">
                <form method="GET" action="{{ route('reports#stockLedger') }}">
                    <input type="date" class="form-control" id="dailyPrintDate" name="dailyPrintDate"
                        value="{{ $selectedDate }}"
                        onchange="this.form.submit()">
                </form>
            </div>
        </div>

        <div id="stock_ledger_list_label" class="row align-items-center bg-white mt-3" style="cursor: pointer;">
            <div class="col-6">
                <label style="cursor: pointer;"><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Stock Ledger Lists</label>
            </div>
            <div class="col-6" style="text-align: right">
                <i class="bx bxs-chevron-down arrow"></i>
            </div>
        </div>

        <div class="stock_ledger_list_container shadow-sm show_container">
            <table id="stock_ledger_list" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>စဥ်</th>
                        <th>ကုန်ပစ္စည်းအမည်</th>
                        <th>ကုန်ဟောင်း</th>
                        <th>ကုန်သစ်</th>
                        <th>ကုန်ထုတ်</th>
                        <th>လက်ကျန်</th>
                        <th>မှတ်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($stockLedgerData) > 0)
                    @foreach($stockLedgerData as $row)
                    <tr>
                        <td>{{ $row['no'] }}</td>
                        <td>{{ $row['item_name'] }}</td>
                        <td>{{ number_format($row['opening_balance']) }}</td>
                        <td>{{ number_format($row['stock_in']) }}</td>
                        <td>{{ number_format($row['stock_out']) }}</td>
                        <td>
                            {{ number_format($row['closing_balance']) }}
                        </td>
                        <td class="{{ $row['remarks'] ? 'negative-stock' : '' }}">{{ $row['remarks'] }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/stock_ledger_script.js') }}"></script>
@endsection