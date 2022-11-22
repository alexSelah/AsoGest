@extends('movil.layout')

@section('content')

<div class="container">
    <div class="form">
    <form action="{{ route('movilInvitacion') }}" method="POST">
        {{ csrf_field() }}
        <h2 class="page_title">{{__('text.MVgastainvitacion')}}</h2>
        <br>
        <div class="form-group">
            <label for="numInvitaciones">{{__('text.MVinvitacionesrest')}}:</label>
            <input type="text" class="form-control" disabled readonly id="numInvitaciones" name="numInvitaciones" value="{{$socio->invitaciones}}">
        </div>
        <div class="form-group">
            <label for="nombre">{{__('text.MVanfitrion')}}</label>
            <input type="text" class="form-control" disabled readonly id="nombre" name="nombre" value="{{$socio->dimeNombre()}}">
            <input type="text" class="hidden" id="idSocio" name="idSocio" value="{{$socio->id}}" style="display: none !important;">
        </div>
        <div class="form-group">
            <label for="invitado">{{__('text.MVnominvitado')}}</label>
            <input type="text" class="form-control" id="invitado" name="invitado">
        </div>
        <div class="form-group">
            <label for="fecha">{{__('text.MVfecha')}}</label>
            <input type="date" class="form-control" id="fecha" name="fecha">
        </div>

        <input type="submit" name="submit" class="btn btn-success" value="Gastar Invitacion" />
        <br>
        <hr>
        <h2>{{__('text.MVinvgastadas')}}</h2>
        @foreach($invitaciones as $invitacion)
            <details>
              <summary>{{\Helper::dimeFechaCarbon($invitacion->fecha,5,"-")}}</summary>
              <p><strong>{{__('text.MVinvitado')}}:</strong>{{$invitacion->invitado}}</p>
            </details>
        @endforeach

        <br>
        <div class="clearfix"></div>
			<div class="scrolltop radius20"><a href="#"><img src="{{ asset('movil/images/icons/top.png')}}" alt="Ir arriba" title="Ir arriba" /></a></div>
    </form>
    </div>
</div>
<!--End of page container-->


<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection
