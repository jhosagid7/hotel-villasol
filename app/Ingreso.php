<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    
    protected $table = 'ingreso';

    protected $primaryKey = 'idingreso';

    public $timestamps = false;

    
    protected $fillabel = [
        'idproveedor',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'estado'
    ];

    
    protected $guarded = [];

    
}
// DELIMITER //
// CREATE TRIGGER tr_udpStockIngreso AFTER INSERT ON detalle_ingreso
// FOR EACH ROW BEGIN
// UPDATE articulo SET stock = stock + NEW.cantidad
// WHERE articulo.idarticulo = NEW.idarticulo;
// END
// //
// DELIMITER ;

// DELIMITER //
// CREATE TRIGGER tr_udpPrecioVentaIngreso AFTER INSERT ON detalle_ingreso
// FOR EACH ROW BEGIN
// UPDATE articulo SET precio_venta = New.precio_compra
// WHERE articulo.idarticulo = NEW.idarticulo;
// END
// //
// DELIMITER ;
