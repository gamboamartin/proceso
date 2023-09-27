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

    /**
     * Obtiene los datos de etapas de una entidad
     * @param string $adm_accion Accion
     * @param string $adm_seccion Seccion
     * @param bool $valida_existencia_etapa Si valida, da error si no existe
     * @param string $pr_etapa_descripcion Descripcion de la etapa a buscar
     * @return array|stdClass
     * @version 9.1.0
     */
    private function data_etapa(string $adm_accion, string $adm_seccion, bool $valida_existencia_etapa,
                                string $pr_etapa_descripcion = ''): array|stdClass
    {
        $adm_accion = trim($adm_accion);
        if($adm_accion === ''){
            return $this->error->error(mensaje: 'Error adm_accion esta vacia', data: $adm_accion);
        }
        $adm_seccion = trim($adm_seccion);
        if($adm_seccion === ''){
            return $this->error->error(mensaje: 'Error adm_seccion esta vacia', data: $adm_seccion);
        }


        $filtro = $this->filtro_etapa_proceso(adm_accion: $adm_accion,adm_seccion: $adm_seccion,
            pr_etapa_descripcion: $pr_etapa_descripcion);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener filtro de etapa', data: $filtro);
        }


        $r_pr_etapa_proceso = (new pr_etapa_proceso(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener conf de etapa', data: $r_pr_etapa_proceso);
        }

        if($valida_existencia_etapa) {
            if ((int)$r_pr_etapa_proceso->n_registros === 0) {
                return $this->error->error(mensaje: 'Error No existe etapa definida', data: $r_pr_etapa_proceso);
            }
        }

        if($r_pr_etapa_proceso->n_registros > 1){
            return $this->error->error(mensaje: 'Error de integridad conf de etapa', data: $r_pr_etapa_proceso);
        }
        return $r_pr_etapa_proceso;
    }

    /**
     * Integra los elementos necesarios para insertar una etapa en el modelo de etapa de la entidad
     * @param string $fecha Fecha de etapa
     * @param string $key_id Key del modelo base
     * @param int $pr_etapa_proceso_id Etapa id
     * @param int $registro_id Registro del modelo base
     * @return array
     * @version 9.3.0
     */
    private function data_insert_etapa(string $fecha, string $key_id, int $pr_etapa_proceso_id,
                                       int $registro_id): array
    {
        $fecha = trim($fecha);
        if($fecha === ''){
            $fecha = date('Y-m-d');
        }
        $key_id = trim($key_id);
        if($key_id === ''){
            return $this->error->error(mensaje: 'Error key_id esta vacio', data: $key_id);
        }
        if($pr_etapa_proceso_id<=0){
            return $this->error->error(mensaje: 'Error pr_etapa_proceso_id es menor a 0', data: $key_id);
        }
        if($registro_id<=0){
            return $this->error->error(mensaje: 'Error registro_id es menor a 0', data: $registro_id);
        }

        $row['pr_etapa_proceso_id'] = $pr_etapa_proceso_id;
        $row[$key_id] = $registro_id;
        $row['fecha'] = $fecha;
        return $row;
    }

    /**
     * Integra los valores necesarios para insersion de una etapa
     * @param string $fecha Fecha de etapa
     * @param string $key_id Key de entidad de integracion
     * @param stdClass $r_pr_etapa_proceso Resultado de busqueda de proceso
     * @param int $registro_id Id de entidad relacionada
     * @return array
     * @version 13.1.0
     */
    PUBLIC function data_row_insert(string $fecha, string $key_id, stdClass $r_pr_etapa_proceso,
                                     int $registro_id): array
    {
        if(!isset($r_pr_etapa_proceso->registros)){
            return $this->error->error(mensaje: 'Error r_pr_etapa_proceso->registro no existe',
                data: $r_pr_etapa_proceso);
        }
        if(!is_array($r_pr_etapa_proceso->registros)){
            return $this->error->error(mensaje: 'Error r_pr_etapa_proceso->registro debe ser un array',
                data: $r_pr_etapa_proceso);
        }
        if(!isset($r_pr_etapa_proceso->registros[0])){
            return $this->error->error(mensaje: 'Error r_pr_etapa_proceso->registro[0] no existe',
                data: $r_pr_etapa_proceso);
        }
        if(!is_array($r_pr_etapa_proceso->registros[0])){
            return $this->error->error(mensaje: 'Error r_pr_etapa_proceso->registro[0] debe ser un array',
                data: $r_pr_etapa_proceso);
        }
        $pr_etapa_proceso = $r_pr_etapa_proceso->registros[0];

        $keys = array('pr_etapa_proceso_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $pr_etapa_proceso);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar pr_etapa_proceso', data: $valida);
        }

        $fecha = trim($fecha);
        if($fecha === ''){
            $fecha = date('Y-m-d');
        }
        $key_id = trim($key_id);
        if($key_id === ''){
            return $this->error->error(mensaje: 'Error key_id esta vacio', data: $key_id);
        }
        if($registro_id<=0){
            return $this->error->error(mensaje: 'Error registro_id es menor a 0', data: $registro_id);
        }

        $data = $this->data_insert_etapa(fecha: $fecha,key_id:  $key_id,
            pr_etapa_proceso_id: $pr_etapa_proceso['pr_etapa_proceso_id'],registro_id:  $registro_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al integrar datos de etapa', data: $data);
        }
        return $data;
    }

    /**
     * Genera el filtro para obtencion de etapa
     * @param string $adm_accion Accion
     * @param string $adm_seccion Seccion
     * @param string $pr_etapa_descripcion Descripcion de la etapa a buscar
     * @return array
     * @version 7.14.0
     */
    private function filtro_etapa_proceso(string $adm_accion, string $adm_seccion,
                                          string $pr_etapa_descripcion = ''): array
    {
        $adm_accion = trim($adm_accion);
        if($adm_accion === ''){
            return $this->error->error(mensaje: 'Error adm_accion esta vacia', data: $adm_accion);
        }
        $adm_seccion = trim($adm_seccion);
        if($adm_seccion === ''){
            return $this->error->error(mensaje: 'Error adm_seccion esta vacia', data: $adm_seccion);
        }
        $pr_etapa_descripcion = trim($pr_etapa_descripcion);
        if($pr_etapa_descripcion !== ''){
            $filtro['pr_etapa.descripcion'] = $pr_etapa_descripcion;
        }
        $filtro['adm_accion.descripcion'] = $adm_accion;
        $filtro['adm_seccion.descripcion'] = $adm_seccion;
        return $filtro;
    }


    private function inserta_data_etapa(string $fecha, modelo $modelo, modelo $modelo_etapa,
                                        stdClass $r_pr_etapa_proceso, int $registro_id){
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

    final public function inserta_etapa(string $adm_accion, string $fecha, modelo $modelo, modelo $modelo_etapa,
                                        int $registro_id, string $pr_etapa_descripcion = '',
                                        bool $valida_existencia_etapa = true){
        $r_pr_etapa_proceso = $this->data_etapa(adm_accion: $adm_accion,adm_seccion:  $modelo->tabla,
            valida_existencia_etapa: $valida_existencia_etapa, pr_etapa_descripcion: $pr_etapa_descripcion);
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