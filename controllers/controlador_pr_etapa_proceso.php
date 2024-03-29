<?php
/**
 * @author Kevin Acuña Vega
 * @version 1.0.0
 * @created 2022-07-07
 * @final En proceso
 *
 */
namespace gamboamartin\proceso\controllers;

use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_etapa_proceso_html;
use gamboamartin\proceso\models\pr_etapa_proceso;
use gamboamartin\system\_ctl_parent_sin_codigo;
use gamboamartin\system\links_menu;

use gamboamartin\template_1\html;
use PDO;
use stdClass;

class controlador_pr_etapa_proceso extends _ctl_parent_sin_codigo {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new pr_etapa_proceso(link: $link);
        $html_ = new pr_etapa_proceso_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id: $this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Etapa Proceso';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = (new pr_etapa_proceso_html(html: $this->html_base))->genera_inputs_alta(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }
        return $r_alta;
    }

    public function modifica(bool $header, bool $ws = false, array $keys_selects = array()): array|stdClass
    {
        $r_modifica = $this->base();
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $r_modifica);
        }

        return $r_modifica;
    }


    private function base(stdClass $params = new stdClass()): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $inputs = (new pr_etapa_proceso_html(html: $this->html_base))->inputs_pr_etapa_proceso(
            controlador:$this, params: $params);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
    }

}