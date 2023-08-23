<?php
namespace tests\controllers;


use gamboamartin\errores\errores;
use gamboamartin\proceso\html\pr_entidad_html;

use gamboamartin\proceso\models\pr_proceso;
use gamboamartin\proceso\tests\base_test;
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

    public function test_data_etapa(): void
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
        $valida_existencia_etapa = false;
        $resultado = $modelo->data_etapa($adm_accion, $adm_seccion, $valida_existencia_etapa);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;

        $del = (new base_test())->del_pr_etapa_proceso(link: $this->link);
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $del);
            print_r($error);exit;
        }

        $alta = (new base_test())->alta_pr_etapa_proceso(link: $this->link,adm_accion_descripcion: 'test');
        if(errores::$error){
            $error = (new errores())->error(mensaje: 'Error al insertar',data:  $alta);
            print_r($error);exit;
        }

        $adm_accion = 'test';
        $adm_seccion = 'adm_accion';
        $valida_existencia_etapa = true;
        $resultado = $modelo->data_etapa($adm_accion, $adm_seccion,'', $valida_existencia_etapa);
        $this->assertIsObject($resultado);
        $this->assertNotTrue(errores::$error);


        errores::$error = false;
    }

    public function test_data_insert_etapa(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';

        $modelo = new pr_proceso(link: $this->link);
        $modelo = new liberator($modelo);

        $fecha = '';
        $key_id = 'a';
        $pr_etapa_proceso_id = 1;
        $registro_id = 1;
        $resultado = $modelo->data_insert_etapa($fecha, $key_id, $pr_etapa_proceso_id, $registro_id);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        $this->assertEquals(1,$resultado['pr_etapa_proceso_id']);
        $this->assertEquals(1,$resultado['a']);
        $this->assertEquals(date('Y-m-d'),$resultado['fecha']);
        errores::$error = false;
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

