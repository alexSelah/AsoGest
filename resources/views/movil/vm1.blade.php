<form action="{{ route('votarPropuestaMovil') }}" method="POST">
    {{ csrf_field() }}
      <input type="text" name="vocaliaActual" id="vocaliaActual" style="display: none !important" value="{{$vocalia['id']}}" />
      <br> <h5>{{__('text.selectPropuestas')}}</h5>

          <select class="selectpicker w-75" multiple data-done-button="true" id="propuestaSelect[]" data-max-options="3" name="propuestaSelect[]">
            @foreach($propuestas as $propuesta)
                <option value='{{$propuesta['id']}}' @if($propuesta['votado']) selected @endif)>{{$propuesta['propuesta']}}
                </option>
            @endforeach
          </select>

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
        <table class="table table-sm">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">{{__('text.propuesta')}}</th>
                <th scope="col">{{__('text.numVotos')}}</th>
            </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 3; $i++)
                    @if(!isset($propuestas[$i]))
                        @switch($i)
                            @case(0)
                                <tr>
                                    <th scope="row"><h5>1º &#129351; </h5></th>
                                    <td>{{$propuestas[$i]['propuesta']}}</td>
                                    <td>{{$propuestas[$i]['numVotos']}}</td>
                                </tr>
                                @break

                            @case(1)
                                <tr>
                                    <th scope="row"><h5>2º 	&#129352; </h5></th>
                                    <td>{{$propuestas[$i]['propuesta']}}</td>
                                    <td>{{$propuestas[$i]['numVotos']}}</td>
                                </tr>
                                @break
                            @case(2)
                                <tr>
                                    <th scope="row"><h5>3º &#129353; </h5></th>
                                    <td>{{$propuestas[$i]['propuesta']}}</td>
                                    <td>{{$propuestas[$i]['numVotos']}}</td>
                                </tr>
                                @break
                        @endswitch
                    @endif
                @endfor
            </tbody>
        </table>
    </div>
    <hr>
    <canvas id="myPieChart" width="1000" height="1000"></canvas>
</div>
