<form action="{{ route('votarPropuestaMovilMP') }}" method="POST">
    {{ csrf_field() }}
    <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocalia['id']}}" />
    <br> <strong> <h5 style="color:green">{{__('text.selectPropuestasSep')}}</h5> </strong>

    <br> <h6>{{__('text.selectPropuestas')}}</h6>
    <div class="form-row justify-content-md-center">
        <br> <h6>{{__('text.M_mayores')}}</h6>
    </div>
    <div class="form-row justify-content-md-center">
        <select class="selectpicker w-auto" multiple data-done-button="true" id="propuestaSelectM[]" data-max-options="3" name="propuestaSelectM[]">
            @foreach($propuestas as $propuesta)
                @if($propuesta['cantidad'] > $cantPropMen)
                    <option value='{{$propuesta['id']}}' @if($propuesta['votado']) selected @endif)>{{$propuesta['propuesta']}}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    <br>
    <div class="form-row justify-content-md-center">
        <br> <h6>{{__('text.M_menores')}}</h6>
    </div>
    <div class="form-row justify-content-md-center">
        <select class="selectpicker w-auto" multiple data-done-button="true" id="propuestaSelectP[]" data-max-options="3" name="propuestaSelectP[]">
            @foreach($propuestas as $propuesta)
                @if($propuesta['cantidad'] <= $cantPropMen)
                    <option value='{{$propuesta['id']}}' @if($propuesta['votado']) selected @endif)>{{$propuesta['propuesta']}}
                    </option>
                @endif
            @endforeach
        </select>
    </div>
    </div>
    <br><hr>
    <div class="panel-footer">
        <button class="btn btn-success" type="submit" data-toggle="modal" data-target="#trabajando_modal">
            &#128499; &nbsp;{{__('text.votar')}}
        </button>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            &#128065; &nbsp;{{__('text.verResultados')}}
        </button>
    </div>
</form>
<br>
<div class="collapse" id="collapseExample">
    <div class="shadow p-3 mb-5 bg-body rounded text-center">
        <p class="text-center"><h4>{{__('text.rankingProps')}}</h4></p>
        <div class="row">
            <p class="text-center"><h5>{{__('text.M_mayores')}}</h5></p>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">{{__('text.propuesta')}}</th>
                    <th scope="col">{{__('text.nVotos')}}</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @for ($i = 0; $i < count($propuestas); $i++)
                        @if($propuestas[$i]['cantidad'] > $cantPropMen)
                            @switch($count)
                                @case(1)
                                    <tr>
                                        <th scope="row"><h5>1º &#129351; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break

                                @case(2)
                                    <tr>
                                        <th scope="row"><h5>2º 	&#129352; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break
                                @case(3)
                                    <tr>
                                        <th scope="row"><h5>3º &#129353; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break
                            @endswitch
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="row">
            <p class="text-center"><h5>{{__('text.M_menores')}}</h5></p>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">{{__('text.propuesta')}}</th>
                    <th scope="col">{{__('text.nVotos')}}</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @for ($i = 0; $i < count($propuestas); $i++)
                        @if($propuestas[$i]['cantidad'] <= $cantPropMen)
                            @switch($count)
                                @case(1)
                                    <tr>
                                        <th scope="row"><h5>1º &#129351; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break

                                @case(2)
                                    <tr>
                                        <th scope="row"><h5>2º 	&#129352; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break
                                @case(3)
                                    <tr>
                                        <th scope="row"><h5>3º &#129353; </h5></th>
                                        <td>{{$propuestas[$i]['propuesta']}}</td>
                                        <td>{{$propuestas[$i]['numVotos']}}</td>
                                    </tr>
                                    @php
                                        $count++;
                                    @endphp
                                    @break
                            @endswitch
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="card card-body">
        <div class="form-row">
            <h5>{{__('text.M_mayores')}}</h5><br>
            <canvas id="myPieChartM"></canvas>
        </div>
        <br>
        <div class="form-row">
            <h5>{{__('text.M_menores')}}</h5><br>
            <canvas id="myPieChartP"></canvas>
        </div>
        </div>

    </div>
</div>
