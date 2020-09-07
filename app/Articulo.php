<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
   //Hacemos referencia a que talla se refiere este modelo
    // protected $table = 'articulo';

    //Decalaramos que atributo va a ser la clave primaria de la tabla
    // protected $primaryKey = 'idarticulo';

    //Para que laravel no nos cree dos columnas en la tabla como cuando se creo y cuando se actualizo
    //debemos especificar el parametro false de lo contrario se coloca true
    //protected $timestamps   = false;
    // public $timestamps = false;

    //Haora debemos especificar cuales son los atributos que deben recibir un valor para almacenar en nuestra tabla
    protected $fillabel = [
        'categoria_id',
        'codigo',
        'nombre',
        'stock',
        'precio_costo',
        'descripcion',
        'imagen',
        'estado'
    ];

    //Haora especificamos los campos guarded
    protected $guarded = [];

    public function articulo_ventas(){
        return hasMany(Articulo_Venta::class);
    }

    public function categoria(){
        return belongsTo(Categoria::class);
    }

}

