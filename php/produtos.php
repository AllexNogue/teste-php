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

    public function alterProd($id, $data)
    {
        // var_dump($id, $data);
        $db = new db();
        $valor = $data['preco'];
        unset($data['preco']); // removemos o preço do array por que o mesmo é alterado em uma tabela separada;

        $result = $db->update($id, 'produtos', $data);

        if($result){
            $db = new db();

            $preco = json_decode($db->getRow('precos', '*', "WHERE id_prod = " . $id), true);

            $alterarPreco = $db->update($preco['id'], 'precos', [
                'preco' => $valor
            ]);

            if($alterarPreco){
                return json_encode(['type' => 'success', 'msg'=> 'Produto alterado.']);
            }else{
                return json_encode(['type' => 'error', 'msg'=> 'Falha ao alterar preço do produto.']);
            }

            
        }else{
            return json_encode(['type' => 'error', 'msg'=> 'Falha ao alterar produto.']);
        }

    }

    public function destroy($id)
    {
        
    }

}

?>