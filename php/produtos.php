<?php

require_once 'db.php';

class Produtos {
    
    /**
     * Função responsavel por retonar os produtos cadastrados
     */
    public function getProd()
    {
        $db = new db();
        $result = $db->select('produtos');
        
        return $result;
    }


    /**
     * Função responsavel por preparar o produto para a inserção no banco de dados
     * Retorna um json para ser tratado pelo javascript
     */
    public function setProd($data){

        $preco = $data['preco'];
        unset($data['preco']); // removemos o preço do array por que o mesmo é inserido em uma tabela separada;

        $db = new db();
        $result = $db->insert('produtos', $data);
        if($result){
            $db = new db();
            $inserirPreco = $db->insert('precos', [
                'id_prod' => $result,
                'preco' => $preco
            ]);

            if($inserirPreco){
                return json_encode(['type' => 'success', 'msg'=> 'Produto inserido.', 'produto' => $result]);
            }else{
                return json_encode(['type' => 'error', 'msg'=> 'Falha ao inserir preço do produto.']);
            }

            
        }else{
            return json_encode(['type' => 'error', 'msg'=> 'Falha ao inserir produto.']);
        }
    }

}

?>