@extends('layouts.admin')

@section('title', trans('pos::general.point_sale'))

@section('content')

    <noscript>You need to enable JavaScript to run this app.</noscript>
    
    <div id="app"></div>

    <script>
        var langPos = {!! json_encode(trans('pos::general')) !!}

        var currency = {
                    thousands: '{{ $currency->thousands_separator }}',
                    decimal: '{{ $currency->decimal_mark }}',
                    precision: '{{ $currency->precision }}',
                    code: '{{ $currency->code }}',
                    allowZero: true,
                    @if($currency->symbol_first)
                    prefix: '{{ $currency->symbol }}'
                    @else
                    suffix: '{{ $currency->symbol }}'
                    @endif
                };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    </script>
    <script src="{{ asset('modules/Pos/Assets/js/app.js') }}"></script>

@stop
