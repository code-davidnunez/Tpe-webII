<?php

require_once('libs/smarty/libs/Smarty.class.php');

    class IndumentariaView {

        private $title;

        function __construct() {
            $this->title  = "Subject";
        }

        function showHome(){
            $smarty = new Smarty();
            $smarty->assign('BASE_URL', BASE_URL);
            $smarty->display('templates/home.tpl');
            
        }

        function showCategorias($categorias,$loginIn){
            $smarty = new Smarty();
            $smarty->assign('BASE_URL', BASE_URL);
            $smarty->assign('categorias', $categorias, false);
            $smarty->assign('loginIn', $loginIn, false);
            $smarty->display('templates/categorias.tpl');
            $smarty-> display('templates/header.tpl');
            
        }

        function showProducto($categoria, $producto,$loginIn){
            $smarty = new Smarty();
            $smarty->assign('BASE_URL', BASE_URL);
            $smarty->assign('categoria', $categoria, true);
            $smarty->assign('producto', $producto, true);
            $smarty->assign('loginIn', $loginIn, false);
            $smarty-> display('templates/tabla.tpl');
            
        }

        function ShowHomeLocation(){
            header("Location: ".BASE_URL."home");
        }
    }
?>