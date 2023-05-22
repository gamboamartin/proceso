<?php
namespace gamboamartin\proceso\tests;
use base\orm\modelo_base;

use gamboamartin\administrador\models\adm_seccion;
use gamboamartin\errores\errores;
use gamboamartin\js_base\eventos\adm_accion;
use gamboamartin\proceso\models\pr_etapa;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\proceso\models\pr_tipo_proceso;
use PDO;
use stdClass;


class base_test{

    public function alta_adm_accion(PDO $link, int $id = 1): array|stdClass
    {

        $alta = (new \gamboamartin\administrador\tests\base_test())->alta_adm_accion(link: $link,id: $id);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }


    public function alta_pr_etapa(PDO $link, int $id = 1): array|stdClass
    {

        $registro = array();
        $registro['id'] = $id;

        $alta = (new pr_etapa($link))->alta_registro(registro: $registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_pr_etapa_proceso(PDO $link, int $adm_accion_id = 1, int $pr_etapa_id = 1, int $id = 1,
                                          int $pr_proceso_id = 1): array|stdClass
    {

        $existe = (new pr_etapa($link))->existe_by_id(registro_id: $pr_etapa_id);
        if(errores::$error){
            return (new errores())->error('Error al validar si existe', $existe);
        }
        if(!$existe) {
            $alta = $this->alta_pr_etapa(link: $link,id: $pr_etapa_id);
            if (errores::$error) {
                return (new errores())->error('Error al insertar adm_seccion', $alta);
            }
        }

        $existe = (new pr_proceso(link: $link))->existe_by_id(registro_id: $pr_proceso_id);
        if(errores::$error){
            return (new errores())->error('Error al validar si existe', $existe);
        }
        if(!$existe) {
            $alta = $this->alta_pr_proceso(link: $link,id: $pr_proceso_id);
            if (errores::$error) {
                return (new errores())->error('Error al insertar pr_proceso_id', $alta);
            }
        }

        $existe = (new \gamboamartin\administrador\models\adm_accion($link))->existe_by_id(registro_id: $adm_accion_id);
        if(errores::$error){
            return (new errores())->error('Error al validar si existe', $existe);
        }
        if(!$existe) {
            $alta = $this->alta_adm_accion(link: $link,id: $adm_accion_id);
            if (errores::$error) {
                return (new errores())->error('Error al insertar adm_seccion', $alta);
            }
        }

        $registro = array();
        $registro['id'] = $id;
        $registro['pr_etapa_id'] = $pr_etapa_id;
        $registro['adm_accion_id'] = $adm_accion_id;
        $registro['pr_proceso_id'] = $pr_proceso_id;

        $alta = (new pr_etapa_proceso($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_pr_proceso(PDO $link, int $id = 1, int $pr_tipo_proceso_id = 1): array|stdClass
    {

        $existe = (new pr_tipo_proceso($link))->existe_by_id(registro_id: $pr_tipo_proceso_id);
        if(errores::$error){
            return (new errores())->error('Error al validar si existe', $existe);
        }
        if(!$existe) {
            $alta = $this->alta_pr_tipo_proceso(link: $link,id: $pr_tipo_proceso_id);
            if (errores::$error) {
                return (new errores())->error('Error al insertar pr_tipo_proceso_id', $alta);
            }
        }


        $registro = array();
        $registro['id'] = $id;
        $registro['pr_tipo_proceso_id'] = $pr_tipo_proceso_id;

        $alta = (new pr_proceso($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }

    public function alta_pr_tipo_proceso(PDO $link, int $id = 1): array|stdClass
    {

        $registro = array();
        $registro['id'] = $id;

        $alta = (new pr_tipo_proceso($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al insertar', data: $alta);
        }
        return $alta;
    }


    


    public function del(PDO $link, string $name_model): array
    {

        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }

    public function del_pr_etapa_proceso(PDO $link): array|\stdClass
    {

        $del = $this->del(link: $link,name_model:  'gamboamartin\\proceso\\models\\pr_etapa_proceso');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);

        }
        return $del;
    }


}
