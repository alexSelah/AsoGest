<!-- Modal Nuevo Socio-->
<div id="nuevoSocio_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <h5><strong>{{ __('text.nuevoSocio') }}</strong></h5> <br>
                    <p class="">{{ __('text.nuevoSocioDescipcion') }}</p>
                    <hr>
                     <form action="{{ route('nuevoSocio') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="inputNumSocio">{{ __('text.numsocio')}}</label>
                                <input class="form-control" type="text" name="inputNumSocio" required readonly id="inputNumSocio" value="{{$ultimoNumSocio}}" />
                            </div>
                            <div class="form-group col-6">
                                <label for="inputDNI"><strong>{{ __('text.DNI')}}</strong></label>
                                <input class="form-control" type="text" name="inputDNI" required id="inputDNI" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="inputNombre" class="form-label text-md-right"><strong>{{ __('text.nombre')}}</strong></label>
                                <input class="form-control" type="text" name="inputNombre" required id="inputNombre"/>
                            </div>
                            <div class="form-group col-6">
                                <label for="inputUsername" class="form-label text-md-right"><strong>{{ __('text.username')}}</strong></label>
                                <input class="form-control" type="text" name="inputUsername" required id="inputUsername" />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="input1apellido" class="form-label text-md-right">{{ __('text.1apellido')}}</label>
                                <input class="form-control" type="text" name="input1apellido" id="input1apellido"/>
                            </div>
                            <div class="form-group col-4">
                                <label for="input2apellido" class="form-label text-md-right">{{ __('text.2apellido')}}</label>
                                <input class="form-control" type="text" name="input2apellido" id="input2apellido" />
                            </div>
                            <div class="form-group col-4">
                                <label for="inputFnacimiento" class="form-label text-md-right"><strong>{{ __('text.fnacimiento')}}</strong></label>
                                <input class="form-control" type="date"  name="inputFnacimiento" required id="inputFnacimiento"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="inputTelefono" class="form-label text-md-right">{{ __('text.telefono')}}</label>
                                <input class="form-control" type="text" name="inputTelefono" id="inputTelefono" />
                            </div>
                            <div class="form-group col-6">
                                <label for="inputEmail" class="form-label text-md-right"><strong>{{ __('text.email')}}</strong></label>
                                <input class="form-control" type="email" name="inputEmail" id="inputEmail"/>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="passwordText" class="form-label text-md-right"><strong>{{ __('text.password')}}</strong></label>
                                <div class="input-group">
                                    <input id="passwordText" required name="passwordText" type="password" class="form-control pwd" placeholder="{{ __('text.nuevaPassword')}}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default reveal" type="button">&#x1f441;</button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-1">
                            </div>
                            <div class="form-group col-5">
                                <input class="form-check-input" type="checkbox" id="descript" name="descript" onclick="cambiabtnaccept()">
                                <label class="form-label" for="descript">{{ __('text.altasocioDesc')}}</label>
                            </div>
                        </div>
                        <hr>

                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-2 justify-content-center">
                                <button class="btn btn-success form-control" disabled id="btnaccept" name="btnaccept" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnGuardar')}}
                                </button>
                            </div>
                            <div class="form-group col-md-2  justify-content-center">

                            </div>
                            <div class="form-group col-md-2  justify-content-center">
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
<!--FIN Modal Nuevo Socio-->

<!-- Modal Importar Fichero Excel-->
<div id="importarExcel_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container-fluid">
            <!-- Modal content-->
            <div class="modal-content col-md-12">
                <div class="modal-body">
                    @if (Auth::user()->hasRole(['secretario','admin']))
                    <form action="{{ route('secretarioImport') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                           <h4>{{__('text.importarExcel')}} </h4>
                           <h6>{{__('text.importarExceltxt')}}</h6>
                           <p>{{__('text.importarExcelAvisoSocios')}}</p>
                           <hr>
                          <div class="form-group row">
                            <label for="excel" class="col-sm-2 col-form-label">{{ __('text.archivo')}}</label>
                            <div class="col-sm-10">
                              <input id="excel" type="file" class="form-control @error('file') is-invalid @enderror" name="excel" value="{{ old('file') }}">
                            </div>
                          </div>
                        <hr>
                        <div class="form-group row justify-content-md-center">
                            <div class="col col-lg-2">
                                <button class="btn btn-success form-control" type="submit" data-toggle="modal" data-target="#trabajando_modal">
                                    {{ __('text.btnImportar')}}
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
                    @else
                        <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <img src="{{ asset('public/images/youshallnotpass.gif') }}" alt="No puedes pasar" class="rounded mx-auto d-block" />
                                    <label><h1><strong>{{ __('text.noAutorizado')}}</strong></h1></label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN Modal Importar Excel-->
