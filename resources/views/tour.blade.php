@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('text.listadoDeDocumentos') }}</strong>
                        <a href="{{ route('welcome') }}"><input type="button" class="btn btn-outline-secondary btn-sm" value="{{ __('text.back')}}" /></a>
                    </div>
                    <div class="card-body">
                        <div class="card-group">
                          <div class="card">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="420" height="315" src="https://www.youtube.com/embed/HQy4W2d_gEc" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="card-body">
                              <h5 class="card-title">Pantalla Inicio</h5>
                              <p class="card-text">Descripción y uso de la Pantalla de Inicio</p>
                            </div>
                            <div class="card-footer">
                              <small class="text-muted">Duración 1:00</small>
                            </div>
                          </div>
                          <div class="card">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="420" height="315" src="//www.youtube.com/embed/BstTBw6BLrE" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="card-body">
                              <h5 class="card-title">Pantalla de Login</h5>
                              <p class="card-text">En esta pantalla podrás iniciar sesión con tus credenciales.</p>
                            </div>
                            <div class="card-footer">
                              <small class="text-muted">Duración 1:00 minutos</small>
                            </div>
                          </div>
                          <div class="card">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe width="420" height="315" src="//www.youtube.com/embed/BstTBw6BLrE" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="card-body">
                              <h5 class="card-title">Card title</h5>
                              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                            </div>
                            <div class="card-footer">
                              <small class="text-muted">Actualizado 31/05/2020</small>
                            </div>
                          </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>

<noscript>Please, enable JavaScript to see this page. Por favor, habilita JavaScript para ver esta página.</noscript>
@endsection

