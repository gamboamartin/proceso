<?php
namespace tests\controllers;


use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_entidad_html;

use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use stdClass;


class pr_procesoTest extends test {
    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
        $this->paths_conf = new stdClass();
        $this->paths_conf->generales = '/var/www/html/cat_sat/config/generales.php';
        $this->paths_conf->database = '/var/www/html/cat_sat/config/database.php';
        $this->paths_conf->views = '/var/www/html/cat_sat/config/views.php';
    }

    public function test_filtro_etapa_proceso(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $adm_accion = 'a';
        $adm_seccion = 'b';
        $resultado = $modelo->filtro_etapa_proceso($adm_accion, $adm_seccion);

        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals('a',$resultado['adm_accion.descripcion']);
        $this->assertEquals('b',$resultado['adm_seccion.descripcion']);
        errores::$error = false;


    }


}

