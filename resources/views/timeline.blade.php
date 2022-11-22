<div class="container">
      
      <!-- Modal nuevo Hito-->
      <div id="crearHito_modal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
              <div class="container-fluid">
                  <!-- Modal content-->
                  <div class="modal-content col-md-16">
                      <div class="modal-body">
                          <label class="label-info">{{ __('text.nuevoHitoTxt') }}</label>
                          <hr>
                          <form action="{{ route('crearHito') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                                
                                <div class="form-group row">
                                   <label for="inputColorHito" class="col-sm-2 col-form-label">{{ __('text.inputColorHito')}}</label>
                                    <div class="col-sm-10">
                                      <select id="inputColorHito" name="inputColorHito">
                                          <option value="danger" data-color="#fe011d"></option>
                                          <option value="primary" data-color="#0067fa" selected></option>
                                          <option value="secondary" data-color="#7d7d7d"></option>
                                          <option value="success" data-color="#05b700"></option>
                                          <option value="light" data-color="#eeeeee"></option>
                                          <option value="warning" data-color="#ffbd00"></option>
                                          <option value="dark" data-color="#2d2d2d"></option>
                                      </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                   <label for="inputIcono" class="col-sm-2 col-form-label">{{ __('text.inputIcono')}}</label>
                                    <div class="col-sm-10">
                                      <input class="form-control" type="text" name="inputIcono" id="inputIcono" placeholder="{{ __('text.inputIconoDesc')}}" required/>
                                    </div>
                                </div>
      
                                <div class="form-group row">
                                   <label for="inputTituloHito" class="col-sm-2 col-form-label">{{ __('text.inputTituloHito')}}</label>
                                    <div class="col-sm-10">
                                      <input class="form-control" type="text" name="inputTituloHito" id="inputTituloHito" placeholder="{{ __('text.inputTituloHitoDesc')}}" required/>
                                    </div>
                                </div>
      
                                <div class="form-group row">
                                   <label for="inputSubtituloHito" class="col-sm-2 col-form-label">{{ __('text.inputSubtituloHito')}}</label>
                                    <div class="col-sm-10">
                                      <input class="form-control" type="text" name="inputSubtituloHito" id="inputSubtituloHito" placeholder="{{ __('text.inputSubtituloHitoDesc')}}" required/>
                                    </div>
                                </div>
      
                                <div class="form-group">
                                    <label for="inputTextoHito">{{__('text.inputTextoHito')}}</label>
                                    <textarea id="inputTextoHito" name="inputTextoHito" class="form-control" placeholder="{{ __('text.inputTextoHitoDesc')}}" rows="4" required></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>
                                
                            <hr>
                            <div class="form-group row justify-content-md-center">
                                <div class="col col-lg-2">
                                    <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                        {{ __('text.btnGuardar')}}
                                    </button>
                                </div>
                                <div class="col-md-auto">
      
                                </div>
                                <div class="col col-lg-2">
                                    <button type="button" class="btn btn-secondary form-control" data-dismiss="modal">
                                        {{ __('text.btnClose')}}
                                    </button>
                                </div>
                            </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
       <!--FIN Modal nuevo Hito-->
      
      
          <div class="page-header">
          @if(Auth::user()->hasAnyPermission('Acceso_total','permiso_tesoreria', 'permiso_secretaria','permiso_ver_informes','permiso_editar_socios'))
            <a><input type="button" class="btn btn-success btn" value="{{ __('text.nuevoHito')}}" data-toggle="modal" data-target="#crearHito_modal"/></a>
            &nbsp; &nbsp; &nbsp;
            <a  href="{{ route('reseteaHitos') }}"><input type="button" class="btn btn-warning btn" value="{{ __('text.resetearHitos')}}" onclick="return confirm('{{ __('text.QuestionSureRestoreHitos')}}')" data-toggle="modal" data-target="#trabajando_modal"/></a>
          @endif
          <h1 id="timeline">{{(__('text.timeLine'))}}</h1>
          </div>
          <ul class="timeline">
          
            <li>
              <div class="timeline-badge danger">&#127922;</div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">{{(__('text.timelineTitle_1'))}}</h4>
                  <p><small class="text-muted">{{(__('text.timelineSubtitle_1'))}}</small></p>
                </div>
                <div class="timeline-body">
                  <p>{{(__('text.timelineText_1'))}}</p>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge info">&#128406;</div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">{{(__('text.timelineTitle_2'))}}</h4>
                  <p><small class="text-muted">{{(__('text.timelineSubtitle_2'))}} {{$fecha}}</small></p>
                </div>
                <div class="timeline-body">
                  <p>{{(__('text.timelineText_2'))}} {{$texto}}</p>
                </div>
              </div>
            </li>
<script>
    $('#inputColorHito').colorselector();
</script>
    </ul>
</div>
{{--IZQUIERDA--}}