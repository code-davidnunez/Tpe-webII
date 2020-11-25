<?php

    require_once("app/Model/ProductoModel.php");
    require_once("app/Model/CategoriaModel.php");
    require_once("app/View/indumentariaView.php");
    require_once("app/Helper/AuthHelper.php");

    class IndumentariaController{

        private $view;
        private $CategoryModel;
        private $ProductModel;
        private $AuthHelper;

        function __construct() {
            $this->view = new IndumentariaView();
            $this->CategoryModel = new CategoryModel();
            $this->ProductModel = new ProductModel();
            $this->AuthHelper =  new AuthHelper();
        }

        // 0.a Funcion para ver el home de la página
        function showHome() {
            $userStatus = $this->AuthHelper->getUserStatus();
            $this->view->renderHome($userStatus);
        }

        // 1.a Funciones para ver Categorys
        function showCategories() {
            $category = $this->CategoryModel->getCategories();
            $userStatus = $this->AuthHelper->getUserStatus();
            $this->view->renderCategories($category, $userStatus);
        }

        // 1.b Funciones para realizar acciones de ABM con la tabla de Categorys
        function insertCategory(){
            $this->AuthHelper->checkLoggedIn();
            if ((isset($_POST['url_img'])&&!empty($_POST['url_img']))
            &&(isset($_POST['coleccion'])&&!empty($_POST['coleccion']))) {
                $this->CategoryModel-> insertCategory($_POST['url_img'],$_POST['coleccion']);
                $this->view->showCategoriesLocation();
            }
        }         

        function deleteCategory($params = null){
            $this->AuthHelper->checkLoggedIn();
            $id_category = $params[':ID'];
            $this->CategoryModel->deleteCategory($id_category);
            $this->view->showCategoriesLocation();
        }

        function editCategory($params = null){
            $this->AuthHelper->checkLoggedIn();
            $id_category = $params[':ID'];
            if ((isset($_POST['url_img'])&&!empty($_POST['url_img']))
            &&(isset($_POST['coleccion'])&&!empty($_POST['coleccion']))) {
                $this->CategoryModel->editCategory($id_category, $_POST['url_img'], $_POST['coleccion']);
                $this->view->showCategoriesLocation();
            }
        }

        // 2.a Funciones para ver los productss
        function showProducts($params = null){
            $id_category = $params[':ID'];
            $category =  $this->CategoryModel->getCategoryById($id_category);
            $products = $this->ProductModel->getProductsByIdCategory($id_category);
            $userData = $this->AuthHelper->getUserStatus();
            $this->view->renderProducts($category[0], $products,$userData);
        }

        function showProductById($params = null){
            $id_product = $params[':ID'];
            $products = $this->ProductModel->getProductsById($id_product);
            $userData = $this->AuthHelper->getUserStatus();
            $this->view->renderProduct($products[0],$userData);
        }

        // 2.b Funciones para realizar acciones de ABM con productss
        function insertProductsInCategoryByGET($params = null){
            $this->AuthHelper->checkLoggedIn();
            $id_category = $params[':ID'];
            if(isset($_POST['color'])&&isset($_POST['talle'])&&isset($_POST['tipo'])) {
                $this->ProductModel->insertProduct($_POST['color'], $_POST['talle'], $_POST['tipo'], $id_category);
            }
            $this->view->showCategoryLocation($id_category);
        }

        function editProducts($params = null){
            $this->AuthHelper->checkLoggedIn();
            $id_products = $params[':ID'];
            if ((isset($_POST['color'])&&!empty($_POST['color']))
            &&(isset($_POST['talle'])&&!empty($_POST['talle']))
            &&(isset($_POST['tipo'])&&!empty($_POST['tipo']))) { 
                $this->ProductModel->editProduct($id_products, $_POST['color'], $_POST['talle'], $_POST['tipo']);
            }
            $this->view->showCategoriesLocation();
        }

        function deleteProducts($params = null){
            $this->AuthHelper->checkLoggedIn();
            $id_products = $params[':ID'];
            $this->ProductModel->deleteProduct($id_products);
            $this->view->showCategoriesLocation();
        }

        // 3.a Función para ver todas las Categorys con sus productss
        function showAllProducts($params = null) {
            $range = ($params[':RANGE'] == null) ? 0 : $params[':RANGE'];
            $range = 0;
            $limit = 5;
            $cantproducts = $this->ProductModel->getCountProducts();
            $products = [];
            $cantproducts = (get_object_vars($cantproducts[0])["COUNT(*)"]);
            if ((isset($_POST['color'])||!empty($_POST['color']))
            ||(isset($_POST['talle'])||!empty($_POST['talle']))
            ||(isset($_POST['prenda'])||!empty($_POST['prenda']))
            ||(isset($_POST['coleccion'])||!empty($_POST['coleccion']))
            ||(isset($_POST['conectorLogico'])||!empty($_POST['conectorLogico']))) {
                $conectorLogico = ($_POST['conectorLogico'] == 1) ? true : false;
                $products = $this->ProductModel->getFilteredProducts($conectorLogico, $range, $limit, $_POST['prenda'], $_POST['color'], $_POST['talle'], $_POST['coleccion']);
            } else {
                $products = $this->ProductModel->getProductsWithCategory($range, $limit);
            }
            $category = $this->CategoryModel->getCategories();
            $userData = $this->AuthHelper->getUserStatus();
            $cantPag = [];
            $aux = 0;
            while ($aux < $cantproducts) {
                array_push($cantPag, $aux);
                $aux += $limit;
                echo $aux < $cantproducts;
            }
            $pag = ($range + 1);
            
            $this->view->renderAllProducsWithCategorys($products,$category, $userData, $cantPag,  $pag);
        }

        // 3.b Función para insertar products en Category por POST
        function insertProductsInCategoryByPOST() {
            $this->AuthHelper->checkLoggedIn();
            if ((isset($_POST['color'])&&!empty($_POST['color']))
            &&(isset($_POST['talle'])&&!empty($_POST['talle']))
            &&(isset($_POST['tipo'])&&!empty($_POST['tipo']))
            &&(isset($_POST['id_category'])&&!empty($_POST['id_category']))) { 
                $this->ProductModel->insertProduct($_POST['color'], $_POST['talle'], $_POST['tipo'], $_POST['id_category']);
                $this->view->showCategoryLocation($_POST['id_category']);
            } else {
                $this->view->showProductsLocation();
            }
        }

        // Busqueda de productos:
        function showFilteredProducts(){
            // if ((isset($_POST['color'])&&!empty($_POST['color']))
            // &&(isset($_POST['talle'])&&!empty($_POST['talle']))
            // &&(isset($_POST['tipo'])&&!empty($_POST['tipo']))
            // &&(isset($_POST['coleccion'])&&!empty($_POST['coleccion']))) { 
            // $userData = $this->AuthHelper->getUserStatus();
            // if ((isset($_POST['conectorLogico'])&&!empty($_POST['conectorLogico']))) { 
            //     $conectorLogico = ($_POST['conectorLogico'] == 1) ? true : false;
            //     $products = $this->ProductModel->getFilteredProducts($conectorLogico, $_POST['prenda'], $_POST['color'], $_POST['talle'], $_POST['coleccion']);
            // }
        }
    }
?>    
