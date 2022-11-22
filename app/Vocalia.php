<?php

namespace App;

use App\AsignacionSocio;
use App\Cuenta;
use App\Compra;
use App\Propuesta;
use App\Calendario;

use Illuminate\Database\Eloquent\Model;

class Vocalia extends Model
{
  	protected $fillable = [
          'nombre','descripcion','presupuesto','imagen'
    ];
   public function asignaciones()
    {
        return $this->hasMany('App\AsignacionSocio');
    }

   public function cuentas()
   {
   		// Relacion que significa que Vocalía tiene muchas Cuentas, y que relaciona el campo "Vocalia" de Cuenta con el campo "nombre" de Vocalía
   		return $this->hasMany('App\Cuenta', 'vocalia','nombre');
   }

   public function compras(){
      return $this->hasMany('App\Compra','vocalia','nombre');
   }

   public function eventos(){
      return $this->hasMany('App\Calendario','idVocalia','id');
   }

   public function propuestas(){
        return $this->hasMany('App\Propuesta','idVocalia','id');
    }

    public function color(){
      if($this['color'] > 0 && $this['color']< 11){
          return $this['color'];
      }else{
          return 7;
      }
    }

 }
