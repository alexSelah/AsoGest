@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                            <div class="texto"> 
                                <h1 class="display-3 text-danger">{{__('text.prohibido')}}</h1>
                                <img id="load_slow" src="{{ asset('images/prohibido.gif') }}" alt="imagen prohibido acceso">
                                <br/><br/>
                                <div>
                                    <br/><br/>
                                    <h1><a href="{{ url('/') }}" class="badge badge-warning">{{ __('text.back') }}</a></h1>
                                </div>
                            </div>
                     </div>
        </div>
    </div>
</div>

@endsection