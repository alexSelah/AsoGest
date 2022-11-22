<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Vocalia;

class Cuenta extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fechaApunte','año','tipo','conceptoAgrupado','detalle','vocalia','cantidad','notas', 'pagcob'
    ];

    protected $dates = ['fechaApunte'];

    public function vocalia()
    {
    	return $this->belongsTo('App\Vocalia','nombre', 'vocalia');
    }

    public function cuotas()
    {
        return $this->belongsTo('App\Cuota','idAsiento','id');
    }

    public function aString(){
       return 'ID: '. $this['id'].'  / fechaApunte: '. $this['FECHA'].'  / AÑO:'.$this['año'].'  / TIPO:'.$this['tipo'].'  / CONCEPTO-AGRUPADO:' .$this['conceptoAgrupado'].'  / DETALLE:' .$this['detalle'].'  / VOCALIA:' .$this['vocalia'].'  / CANTIDAD:' .$this['cantidad'].'  / NOTAS:' .$this['notas'].'  / PAG-COB:'.$this['pagcob'];
   }
}
