<?php
namespace gamboamartin\proceso\models;
use base\orm\_modelo_parent;
use PDO;

class pr_proceso extends _modelo_parent {

    public function __construct(PDO $link){
        $tabla = 'pr_proceso';
        $columnas = array($tabla=>false,'pr_tipo_proceso'=>$tabla);
        $campos_obligatorios[] = 'descripcion';
        $campos_obligatorios[] = 'descripcion_select';

        $tipo_campos['codigos'] = 'cod_1_letras_mayusc';



        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, tipo_campos: $tipo_campos);

        $this->NAMESPACE = __NAMESPACE__;
    }


}