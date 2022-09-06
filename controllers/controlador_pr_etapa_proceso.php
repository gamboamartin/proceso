<?php
/**
 * @author Kevin Acuña Vega
 * @version 1.0.0
 * @created 2022-07-07
 * @final En proceso
 *
 */
namespace controllers;

use gamboamartin\system\links_menu;
use gamboamartin\system\system;

use html\pr_etapa_proceso_html;
use models\pr_etapa_proceso;
use PDO;
use stdClass;

class controlador_pr_etapa_proceso extends system {

    public function __construct(PDO $link, stdClass $paths_conf = new stdClass()){
        $modelo = new pr_etapa_proceso(link: $link);
        $html = new pr_etapa_proceso_html();
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Etapa_Proceso';

    }

}