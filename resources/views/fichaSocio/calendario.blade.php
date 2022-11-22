@if( $eventosCal == null)
    <h2>{{ __('errortext.noEventos')}}</h2>
@else
    <table class="table table-striped table-bordered text-center" id="tablaejercicioes">
        <thead class="thead-light">
            <tr>
                <th>{{ __('text.fecha')}}</th>
                <th>{{ __('text.colDescripcion')}}</th>
                <th>{{ __('text.vocalia')}}</th>
                <th>{{ __('text.eliminar')}}</th>
            </tr>
        </thead> 
        <tbody>
            <tr>
                @foreach ($eventosCal as $evento)
                <tr>
                    <td style="display:none !important;"><input type="text" id="id[]" name="id[]" value="{{$evento['id']}}"></td>
                    <td class="align-middle">{{$evento['fecha']}}</td>
                    <td class="align-middle">{{$evento['descripcion']}}</td>
                    <td class="align-middle">{{$evento['vocalia']}}</td>
                    <td class="align-middle">
                        <a href="{{url('/eliminaEvento')}}/{{$evento['id']}}"><input type="button" class="btn btn-warning" value="&#9003;" /></a>
                    </td>
                </tr>
                @endforeach
            </tr> 
        </tbody>
    </table>
@endif