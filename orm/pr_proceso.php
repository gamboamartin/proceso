<?php
namespace gamboamartin\proceso\models;
use base\orm\_modelo_parent;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

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

    private function data_etapa(string $adm_accion, string $adm_seccion){
        $filtro = $this->filtro_etapa_proceso(adm_accion: $adm_accion,adm_seccion: $adm_seccion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener filtro de etapa', data: $filtro);
        }


        $r_pr_etapa_proceso = (new pr_etapa_proceso(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener conf de etapa', data: $r_pr_etapa_proceso);
        }

        if($r_pr_etapa_proceso->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad conf de etapa', data: $r_pr_etapa_proceso);
        }
        return $r_pr_etapa_proceso;
    }

    private function data_insert_etapa(string $fecha, string $key_id, int $pr_etapa_proceso_id, int $registro_id): array
    {
        if($fecha === ''){
            $fecha = date('Y-m-d');
        }
        $row['pr_etapa_proceso_id'] = $pr_etapa_proceso_id;
        $row[$key_id] = $registro_id;
        $row['fecha'] = $fecha;
        return $row;
    }

    private function data_row_insert(string $fecha, string $key_id, stdClass $r_pr_etapa_proceso, int $registro_id): array
    {
        $pr_etapa_proceso = $r_pr_etapa_proceso->registros[0];

        return $this->data_insert_etapa(fecha:$fecha,
            key_id: $key_id, pr_etapa_proceso_id: $pr_etapa_proceso['pr_etapa_proceso_id'],
            registro_id:  $registro_id);
    }

    private function filtro_etapa_proceso(string $adm_accion, string $adm_seccion): array
    {
        $filtro['adm_accion.descripcion'] = $adm_accion;
        $filtro['adm_seccion.descripcion'] = $adm_seccion;
        return $filtro;
    }


    private function inserta_data_etapa(string $fecha, modelo $modelo, modelo $modelo_etapa, stdClass $r_pr_etapa_proceso, int $registro_id){
        $etapa = new stdClass();
        if($r_pr_etapa_proceso->n_registros === 1){

            $row = $this->data_row_insert(fecha: $fecha,
                key_id:  $modelo->key_id, r_pr_etapa_proceso: $r_pr_etapa_proceso,registro_id:  $registro_id);

            $etapa = $modelo_etapa->alta_registro(registro: $row);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar data', data: $etapa);
            }

        }
        return $etapa;
    }

    final public function inserta_etapa(string $adm_accion, string $fecha, modelo $modelo, modelo $modelo_etapa, int $registro_id){
        $r_pr_etapa_proceso = $this->data_etapa(adm_accion: $adm_accion,adm_seccion:  $modelo->tabla);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener conf de etapa', data: $r_pr_etapa_proceso);
        }

        $r_alta_factura_etapa = $this->inserta_data_etapa(fecha: $fecha,modelo:  $modelo, modelo_etapa: $modelo_etapa,
            r_pr_etapa_proceso:  $r_pr_etapa_proceso, registro_id: $registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al insertar etapa', data: $r_alta_factura_etapa);
        }
        return $r_alta_factura_etapa;
    }


}