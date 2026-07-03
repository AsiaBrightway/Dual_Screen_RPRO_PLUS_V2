@extends('layouts.admin.master')
@section('title', 'Closing Balance Entry')
<link rel="stylesheet" href="{{ asset('css/closing_balance_entry_style.css') }}">

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

    .closing-balance-input {
        width: 120px;
        text-align: right;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 14px;
    }

    .closing-balance-input:focus {
        border-color: #512DA8;
        outline: none;
        box-shadow: 0 0 0 2px rgba(81, 45, 168, 0.2);
    }

    /* Chrome, Safari, Edge, Opera */
    .closing-balance-input::-webkit-outer-spin-button,
    .closing-balance-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    .closing-balance-input[type=number] {
        -moz-appearance: textfield;
    }

    .save-btn-container {
        display: flex;
        justify-content: flex-end;
        padding: 15px 0;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        padding: 12px 20px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>
<section class="home-section">
    <div class="home-title">
        <i class='bx bx-menu'></i>
        <span class="text">Closing Balance Entry</span>
    </div>

    <div class="home-content">
        <div class="table_buttons_container mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2" style="margin-right:11px">
            <div>
                @if(session('success'))
                <div class="alert-success mb-0" style="padding: 4px 16px; font-size: 14px">
                    <i class="fa-solid fa-circle-check" style="padding-right: 5px;"></i> {{ session('success') }}
                </div>
                @endif
            </div>
            <div class="d-flex flex-column flex-md-row gap-2">
                <input type="date" class="form-control" value="{{ $today }}" min="{{ $today }}" max="{{ $today }}">
                <a href="{{ route('reports#stockLedger') }}" class="btn btn-sm custom_btn d-flex align-items-center text-nowrap" onclick="this.classList.add('disabled');">
                    <i class="fa-solid fa-arrow-left" style="padding-right: 5px;"></i>
                    Back to Stock Ledger
                </a>
            </div>
        </div>

        <div id="closing_balance_entry_label" class="row align-items-center bg-white mt-3" style="cursor: pointer;">
            <div class="col-6">
                <label style="cursor: pointer;"><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Closing Balance Entry</label>
            </div>
            <div class="col-6" style="text-align: right">
                <i class="bx bxs-chevron-down arrow"></i>
            </div>
        </div>

        <div class="closing_balance_entry_container shadow-sm show_container">
            <form method="POST" action="{{ route('reports#saveClosingBalance') }}" id="closingBalanceForm">
                @csrf
                <table id="closing_balance_entry_table" class="table table-striped nowrap" style="width:100%,">
                    <thead>
                        <tr>
                            <th>စဥ်</th>
                            <th>ကုန်ပစ္စည်းအမည်</th>
                            <th>ကုန်ဟောင်း</th>
                            <th>ကုန်သစ်</th>
                            <th>လက်ကျန် (ထည့်ရန်)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($entryData) > 0)
                        @foreach($entryData as $row)
                        <tr>
                            <td>{{ $row['no'] }}</td>
                            <td>{{ $row['item_name'] }}</td>
                            <td>{{ number_format($row['opening_balance']) }}</td>
                            <td>{{ number_format($row['stock_in']) }}</td>
                            <td>
                                <input type="number"
                                    class="closing-balance-input"
                                    name="items[{{ $row['item_id'] }}]"
                                    value="{{ $row['closing_balance'] }}"
                                    step="1"
                                    min="0">
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="save-btn-container">
                    <button type="submit" class="btn custom_btn" id="saveClosingBalanceBtn" onclick="this.disabled=true; this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\' style=\'padding-right:5px;\'></i> သိမ်းနေသည်...'; this.form.submit();">
                        <i class="fa-solid fa-floppy-disk" style="padding-right: 5px;"></i>
                        သိမ်းရန်
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/closing_balance_entry_script.js') }}"></script>
@endsection