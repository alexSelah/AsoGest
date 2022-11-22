@extends('layouts.app')

@section('content')

<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ __('text.TEXTO') }}</strong>
                        <a href="{{ url()->previous() }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>  
                     <br>
                    {{ __('text.DESCRIPCION') }}
                    <br>

                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
</script>

@endsection