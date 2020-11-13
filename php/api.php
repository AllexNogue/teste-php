<?php

require 'produtos.php';


/**
 * Separa qual ação será feita e preapara os dados
 */

switch($_POST['action']){
    case 'insert':
        $data = [
            'nome' => $_POST['nome'],
            'cor' => $_POST['cor'],
            'preco' => $_POST['preco'],
        ];

        $produtos = new Produtos();
        header('Content-Type: application/json');
        echo $produtos->insert($data);

    break;
    case 'select': 
        $produtos = new Produtos();
        header('Content-Type: application/json');
        if(isset($_POST['filtro'])){
            echo $produtos->get($_POST['filtro'], $_POST['valor']);
        }else{
            echo $produtos->get();
        }

    break;

    case 'update': 
        $produtos = new Produtos();
        $id = $_POST['id'];
        $data = [
            'nome' => $_POST['nome'],
            'cor' => $_POST['cor'],
            'preco' => $_POST['preco'],
        ];
        header('Content-Type: application/json');
        echo $produtos->alter($id, $data);

    break;

    case 'delete': 
        $produtos = new Produtos();
        $id = $_POST['id'];
        header('Content-Type: application/json');
        echo $produtos->destroy($id);

    break;

    default:
    
        die('action undefined!');
}




?>