<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposCuota extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion', 'meses', 'cantidad','invitaciones',
    ];

    public function mesesCuota(){
        return $this->meses;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getCantidad(){
        return $this->cantidad;
    }
}
