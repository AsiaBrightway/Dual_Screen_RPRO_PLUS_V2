@extends('layouts.admin.master')
@section('title', 'Table-Reservation')

@section('content')
    <style>
        .table_div::-webkit-scrollbar,
        .order_div::-webkit-scrollbar {
            display: none;
        }

        .table_div,
        .order_div {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .table-button.selected {
            color: #512DA8 !important;
            border-color: #512DA8;
        }

        .btn-group {
            white-space: nowrap;
        }

        .btn-custom {
            --bs-btn-color: #fff;
            --bs-btn-bg: #6f44d1;
            --bs-btn-border-color: #6f44d1;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #6f44d1;
            --bs-btn-hover-border-color: #512DA8;
            --bs-btn-focus-shadow-rgb: 49, 132, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #512DA8;
            --bs-btn-active-border-color: #512DA8;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #6f44d1;
            --bs-btn-disabled-border-color: #6f44d1;
        }

        .custom-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-text {
            margin-left: 0px;
            /* Adjust margin as needed */
        }

        .custom-label {
            margin-left: auto;
            margin-right: 25px;
        }

        .button-link {
            background: none;
            border: none;
            /* Set the desired link color */
            text-decoration: none;
            /* cursor: pointer; */
            color: #512DA8
        }

        .button-link:hover {
            text-decoration: none;
            /* Remove underline on hover if desired */
        }

        /* Existing styles */
        .visually-hidden {
            position: absolute;
            overflow: hidden;
            clip: rect(0 0 0 0);
            height: 1px;
            width: 1px;
            margin: -1px;
            padding: 0;
            border: 0;
        }

        /* Additional styles for gift icon */
        .gift-icon {
            color: whitesmoke;
            /* Default color */
        }

        .gift-checkbox:checked+label .gift-icon {
            color: #512DA8;
            /* Change color when checkbox is checked */
        }
    </style>
    <section class="home-section">
        <div class="home-title custom-title">
            <i class='bx bx-menu'></i>
            <span class="text custom-text">Table-Reservation</span>
            <label class="custom-label" style="color:#512DA8; font-weight:bold"><i class="fa-solid fa-calendar-days"
                    style="padding-right: 5px"></i>
                {{ now()->format('l, F j, Y') }}</label>
        </div>
        <div class="home-content">

            <div class="row justify-content-between mt-2">
                <div class="col-6 left_div">
                    <div class="row justify-content-between pt-3 pb-3 table_status"
                        style="background: white; border-radius:10px; margin-right:5px; margin-left:0px">
                        <div class="col" style="text-align:center; color:rgb(54, 50, 50)">
                            <label id="availableLabel" style="font-weight: bold"></label>
                        </div>
                        <div class="col" style="text-align:center; color:orange">
                            <label id="reservationLabel" style="font-weight: bold"></label>
                        </div>
                        <div class="col" style="text-align:center; color:red">
                            <label id="occupiedLabel" style="font-weight: bold"></label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="table_div justify-content-around"
                            style="height:75vh;overflow-y: auto; padding-left:10px;">
                            @php
                                $totalTableCount = 0;
                                $availableTableCount = 0;
                                $reservationTableCount = 0;
                                $occupiedTableCount = 0;
                            @endphp
                            @if (count($tables) != 0)
                                @foreach ($tables as $table)
                                    @php
                                        $totalTableCount++;
                                        $occupied = false;
                                        $reserved = false;
                                        foreach ($occupiedTables as $occupiedTable) {
                                            if ($table['table_id'] == $occupiedTable['table_id']) {
                                                $occupied = true;
                                                $occupiedTableCount++;
                                                break;
                                            }
                                        }
                                        foreach ($reservationTables as $reservationTable) {
                                            if ($table['table_id'] == $reservationTable['table_id']) {
                                                $reserved = true;
                                                $reservationTableCount++;
                                                break;
                                            }
                                        }
                                        $disabled = $occupied ? 'disabled' : '';
                                        $backgroundColor = $occupied ? 'red' : ($reserved ? 'orange' : 'white');
                                        $textColor = $occupied ? 'white' : ($reserved ? 'white' : 'black');
                                    @endphp
                                    <button class="btn m-2 table-button"
                                        style="width: 105px; height: 105px; border-radius: 20px; background: {{ $backgroundColor }}; color: {{ $textColor }}"
                                        data-table-value="{{ $table['table_name'] }}"
                                        data-table_id="{{ $table['table_id'] }}" data-floor_id="{{ $table['floor_id'] }}"
                                        data-floor_name="{{ $table['floor_name'] }}" {{ $disabled }}>
                                        {{ $table['table_name'] }}
                                    </button>
                                @endforeach
                                @php
                                    $availableTableCount =
                                        $totalTableCount - ($occupiedTableCount + $reservationTableCount);
                                @endphp
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="btn-group" role="group" style="height: 40px; overflow-x:auto">
                            @if (count($floors) != 0)
                                @foreach ($floors as $floor)
                                    <input type="radio" class="btn-check" name="btnradio"
                                        id="{{ $floor['floor_id'] }}" autocomplete="off"
                                        value="{{ $floor['floor_id'] }}"
                                        @if ($loop->first) checked @endif>
                                    <label class="btn btn-custom"
                                        for="{{ $floor['floor_id'] }}">{{ $floor['floor_name'] }}</label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col right_div" style="margin-left:50px;margin-right: 15px">
                    <div class="row ">
                        <div class="container" style="height: 100%; background:white; border-radius:20px; padding:30px">
                            <div style="display: flex; justify-content:center ">
                                <h4 style="color:#512DA8; font-weight:bold">Reservation Info</h4>
                            </div>
                            <hr style="margin:10px 0 10px 0">
                            <div class="reservation_info_div container"
                                style="height: 70vh; border-radius:20px; overflow-y:auto">
                                {{-- <form action="{{ route('reservation#create') }}" method="POST" id="reservationForm">
                                    @csrf --}}
                                    <div class="row mb-4 mt-4">
                                        <div class="col-6">
                                            <label class="form-label">Reservation Date</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="date"
                                                class="form-control @error('reservation_date') is-invalid @enderror"
                                                id="reservation_date" name="reservation_date"
                                                value="{{ old('reservation_date') }}">
                                            @error('reservation_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="form-label">Reservation Time</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="time"
                                                class="form-control @error('reservation_time') is-invalid @enderror"
                                                id="reservation_time" name="reservation_time"
                                                value="{{ old('reservation_time') }}">
                                            @error('reservation_time')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-3 mb-4 mt-4">
                                        <div class="col-6">
                                            <label class="form-label">Table</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control muted" id="table_name"
                                                name="table_name" value="{{ old('table_name') }}" readonly>
                                            <input type="text" id="table_id" name="table_id"
                                                value="{{ old('table_id') }}" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="form-label">Name</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="form-label">Phone Number</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="text"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                            @error('phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <label class="form-label">Number of Person</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="number"
                                                class="form-control @error('number_of_person') is-invalid @enderror"
                                                min="1" id="number_of_person" name="number_of_person"
                                                value="{{ old('number_of_person') }}">
                                            @error('number_of_person')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                {{-- </form> --}}
                            </div>
                            <hr style="margin:10px 0 10px 0">
                            <div class="container">
                                <div class="row mt-3 formButton_div">
                                    <button type="submit" class="btn"
                                        style="background: #512DA8; color:white;font-weight:bold; height:50px;"
                                         id="reserveBtn" disabled>Reserve</button>
                                </div>
                            </div>
                        </div>
                        <form id="reservationFormReload" method="GET" action="{{ route('store#reservationPage') }}">
                            @csrf
                            <input type="hidden" name="tableID" id="tableID">

                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Modal --}}
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="success-card">      
                    <button id="btn_successOK" class="btn-cross-custom" data-bs-dismiss="modal">
                        <i class="fa-solid fa-x"></i>
                    </button>
                    
                    <div class="icon-wrapper">
                        <div class="icon-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-content">
                        <h2 class="success-title">
                            Success
                        </h2>
                        <p class="success-desc">
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>

    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/table_reservation_script.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#availableLabel').text("Available - " + {{ $availableTableCount }});
            $('#reservationLabel').text("Reservation - " + {{ $reservationTableCount }});
            $('#occupiedLabel').text("Occupied - " + {{ $occupiedTableCount }});

           $(document).on('click', '#reserveBtn', function(e) {
                e.preventDefault();

                let reserveLog = {
                    _token: '{{ csrf_token() }}',
                    table_id: $('#table_id').val(), 
                    name: $('#name').val(),
                    phone_number: $('#phone_number').val(),
                    number_of_person: $('#number_of_person').val(),
                    reservation_date: $('#reservation_date').val(),
                    reservation_time: $('#reservation_time').val()
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ route("reservation#create") }}',
                    data: reserveLog,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('.success-desc').text('Reservation created successfully.');
                            $('#successModal').modal('show');
                        }
                    },
                })
            })

            $('#btn_successOK').click(function() {
                $('#successModal').modal('hide');
                location.reload(); 
            });

            // Also handle when modal is fully hidden (in case user clicks outside)
            // $('#successModal').on('hidden.bs.modal', function () {
            //     location.reload();
            // });
        })

    </script>

@endsection
