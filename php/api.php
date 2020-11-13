<?php

require 'produtos.php';

switch($_POST['action']){
    case 'insert':

        $data = [
            'nome' => $_POST['nome'],
            'cor' => $_POST['cor'],
            'preco' => $_POST['preco'],
        ];

        $produtos = new Produtos();
        header('Content-Type: application/json');
        echo $produtos->setProd($data);

    break;
    case 'select': 
        $produtos = new Produtos();
        header('Content-Type: application/json');
        echo $produtos->getProd();

    break;

    case 'update': 
        $produtos = new Produtos();
        $id = $_POST['id'];
        $data = [
            'nome' => $_POST['nome'],
            'cor' => $_POST['cor'],
            'preco' => $_POST['preco'],
        ];
        // header('Content-Type: application/json');
        echo $produtos->alterProd($id, $data);

    break;

    default:
    
        die('action undefined!');
}




?>