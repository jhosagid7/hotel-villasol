<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //Hacemos referencia a que talla se refiere este modelo
    protected $table        = 'categoria';

    //Decalaramos que atributo va a ser la clave primaria de la tabla
    protected $primaryKey   = 'idcategoria';

    //Para que laravel no nos cree dos columnas en la tabla como cuando se creo y cuando se actualizo 
    //debemos especificar el parametro false de lo contrario se coloca true
    //protected $timestamps   = false;
    public $timestamps = false;

    //Haora debemos especificar cuales son los atributos que deben recibir un valor para almacenar en nuestra tabla
    protected $fillabel     = [
        'nombre',
        'descripcion',
        'condicion'
    ];

    //Haora especificamos los campos guarded
    protected $guarded=[

    ];
}
