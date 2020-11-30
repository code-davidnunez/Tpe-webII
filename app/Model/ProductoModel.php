<?php

    require_once("app/Helper/DataBaseHelper.php");

    class ProductModel {

        private $db;

        function __construct() {
            $this->db = DataBaseHelper::connection();
        }

        function getCountProducts(){
            $query = $this->db->prepare("SELECT COUNT(*) FROM producto");
            $query->execute(array());
            return  $query->fetchAll(PDO::FETCH_OBJ);
        }

        // function getFilteredProducts($conectorLogico, $n1, $n2, $color,$talle,$tipo, $categoria){
        function getFilteredProducts($conectorLogico, $n1, $n2, $tipo,$color,$talle, $categoria){
            $sentence = '';
            $conector = '';
            // $queryValues = array();
            $arr = array(
                'producto.tipo' =>$tipo,
                'producto.color' =>$color,
                'producto.talle' =>$talle,
                'categoria.coleccion' =>$categoria,
            );
            foreach(array_keys($arr) as $e) {
                if($arr[$e]!= null) {
                    $sentence .= ' '.$conector.' LOWER(  '.$e.' ) LIKE LOWER("%'.$arr[$e].'%")';
                    $conector = ($conectorLogico == true) ? 'AND': 'OR';
            }
            }
            $a ='SELECT * FROM producto INNER JOIN categoria ON categoria.id = producto.id_categoria WHERE '.$sentence.'LIMIT '.$n1.', 5';
            echo($a);
            $query = $this->db->prepare($a);
            $query->execute();
            return  $query->fetchAll(PDO::FETCH_OBJ);
        }
        
        function getProductsWithCategory($n1, $n2){
            // echo 'SELECT * FROM categoria INNER JOIN producto ON categoria.id = producto.id_categoria BETWEEN '.$n1.' AND '.$n2.'';die();
            $query = $this->db->prepare('SELECT * FROM categoria INNER JOIN producto ON categoria.id = producto.id_categoria LIMIT '.$n1.', 5');
            $query->execute(array());
            return  $query->fetchAll(PDO::FETCH_OBJ);
        }

        function getProductsByIdCategory($id){
            $query = $this->db->prepare("SELECT producto.*, categoria.coleccion FROM producto INNER JOIN categoria ON categoria.id = producto.id_categoria WHERE id_categoria=?");
            $query->execute(array($id));
            return  $query->fetchAll(PDO::FETCH_OBJ);

            
        }

        function getProductsById($id){
            $query = $this->db->prepare("SELECT producto.id,producto.tipo,producto.color,producto.talle,producto.id_categoria,producto.img, categoria.coleccion FROM producto,categoria WHERE  producto.id =? AND producto.id_categoria = categoria.id");
            $query->execute(array($id));
            return  $query->fetchAll(PDO::FETCH_OBJ);
        }

        function insertProduct($color,$talle,$tipo, $id_category, $img){
            $query = $this->db->prepare("INSERT INTO producto(color,talle,tipo,id_categoria,img) VALUES(?,?,?,?,?)");           
            $query->execute(array($color,$talle,$tipo, $id_category, $img));
            
        }

        function deleteProduct($id){
            $query = $this->db->prepare("DELETE FROM producto WHERE id=?");
            $query->execute(array($id));
        }

        function editProduct($tipo,$color,$talle,$id){
            $query = $this->db->prepare("UPDATE producto SET tipo=?, color=?, talle=? WHERE id=?");
            $query->execute(array($tipo,$color,$talle,$id));
        }

        function editImage($img,$id){
            $query = $this->db->prepare("UPDATE producto SET img=? WHERE id=?");
            $query->execute(array($img,$id));
        }
        
        function deleteImage($id){
            $query = $this->db->prepare('UPDATE producto SET img= ? WHERE id=?');
            $query->execute(array(null,$id));
        }
    }
?>


