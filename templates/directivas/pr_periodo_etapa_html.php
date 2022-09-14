<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\proceso\controllers\controlador_pr_etapa;
use gamboamartin\system\html_controler;
use models\pr_etapa;
use models\pr_nom_etapa;
use models\pr_periodo_etapa;
use PDO;
use stdClass;


class pr_periodo_etapa_html extends html_controler {

    public function select_pr_periodo_etapa_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                           bool $disabled = false, bool $required = false): array|string
    {
        $modelo = new pr_periodo_etapa(link: $link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo, disabled: $disabled,label: 'Etapas Periodo',required: $required);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }
}
