<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\TiposCuota;

class Cuota extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idSocio','idAsiento','tipoCuota', 'cantidad', 'fechaCuota',
    ];

    protected $dates = ['fechaCuota'];

    public function socio()
    {
    	return $this->belongsTo('App\User', 'idSocio','id');
    }

    public function cuenta()
    {
        return $this->belongsTo('App\Cuenta', 'idAsiento','id');
    }

    public function participacion ()
    {
        $mesesC = TiposCuota::where('id',$this->tipoCuota)->first()->mesesCuota();
        return ($this->cantidad / $mesesC);
    }

    public function venceCuota(){
        $mesesC = TiposCuota::where('id',$this->tipoCuota)->first()->mesesCuota();
        return ($this->fechaCuota)->addMonths($mesesC);
    }
}

