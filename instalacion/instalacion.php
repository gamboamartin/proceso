<?php
namespace gamboamartin\proceso\instalacion;
use gamboamartin\administrador\models\_instalacion;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class instalacion
{
    private function _add_pr_etapa_proceso(PDO $link): array|stdClass
    {
        $out = new stdClass();
        $init = (new _instalacion(link: $link));

        $create = $init->create_table_new(table: 'pr_etapa_proceso');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al agregar tabla', data:  $create);
        }
        $out->create = $create;

        $columnas = new stdClass();
        $add_colums = $init->add_columns(campos: $columnas,table:  'pr_etapa_proceso');
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al agregar columnas', data:  $add_colums);
        }
        $out->add_colums_base = $add_colums;


        $foraneas = array();
        $foraneas['pr_proceso_id'] = new stdClass();
        $foraneas['pr_etapa_id'] = new stdClass();
        $foraneas['adm_accion_id'] = new stdClass();

        $result = $init->foraneas(foraneas: $foraneas,table:  'pr_etapa_proceso');

        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al ajustar foranea', data:  $result);
        }

        $out->foraneas = $result;



        return $out;
    }
    private function pr_etapa_proceso(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $create = $this->_add_pr_etapa_proceso(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al agregar tabla', data:  $create);
        }

        $out->campos = $create;

        return $out;

    }

    final public function instala(PDO $link): array|stdClass
    {
        $out = new stdClass();

        $pr_etapa_proceso = $this->pr_etapa_proceso(link: $link);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error integrar pr_etapa_proceso', data:  $pr_etapa_proceso);
        }


        return $out;

    }

}
