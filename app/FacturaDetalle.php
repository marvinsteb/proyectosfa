<?php

namespace proyectoSeminario;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    protected $table='fac_detalle';

    protected $primaryKey = 'idfac_detalle';

    public $timestamps = false ;
    protected $fillable = [
         'idfactura',
         'id_inv',
         'cantidad',
         'precio',
         'impuesto',
        
    ];
    protected $guarded = [
        'subtotal',
    ]; 
}
