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
use gamboamartin\system\links_menu;
use gamboamartin\system\system;

use gamboamartin\template_1\html;
use html\pr_etapa_html;
use html\pr_nom_etapa_html;
use models\pr_etapa;
use models\pr_nom_etapa;
use PDO;
use stdClass;

class controlador_pr_nom_etapa extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new pr_nom_etapa(link: $link);
        $html_ = new pr_nom_etapa_html(html: $html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Etapas Nomina';
    }

}