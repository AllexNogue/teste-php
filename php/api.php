<?php

require 'produtos.php';

switch($_POST['action']){
    case 'insert':

        $data = [
            'nome' => $_POST['name'],
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
    default:
    
        die('action undefined!');
}




?>