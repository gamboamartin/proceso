<?php
namespace gamboamartin\proceso\models;
use base\orm\modelo;
use PDO;

class pr_etapa extends modelo{

    public function __construct(PDO $link){
        $tabla = 'pr_etapa';
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }
}