<?php

require_once 'db.php';

class Produtos {
    
    /**
     * Função responsavel por retonar os produtos cadastrados
     * Filtro: retorna produtos de acordo com o filtro solicitado
     */
    public function get($filtro = null, $valor = null)
    {
        $db = new db();
        $sql = 'SELECT p.*, pr.preco FROM produtos p INNER JOIN precos pr ON pr.id_prod = p.id';

        if(!is_null($filtro)){
            switch($filtro){
                case 'nome':
                    $sql .= ' WHERE p.nome LIKE "%'.$valor.'%"';
                break;
                case 'cor':
                    $sql .= ' WHERE p.cor LIKE "%'.$valor.'%"';
                break;
                case 'preco1':
                    $sql .= ' WHERE pr.preco > "'. $valor .'"';
                break;
                case 'preco2':
                     $sql .= ' WHERE pr.preco < "'. $valor .'"';
                break;
                case 'preco3':
                     $sql .= ' WHERE pr.preco = "'. $valor .'"';
                break;
            }            
        }
        
        $result = $db->pure($sql);
        
        return $result;
    }


    /**
     * Função responsavel por preparar o produto para a inserção no banco de dados
     * Retorna um json para ser tratado pelo javascript
     */
    public function insert($data){

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

    
    public function alter($id, $data)
    {
        $db = new db();
        $valor = $data['preco'];

        //Por garantia removemos a cor.
        unset($data['preco'], $data['cor']); // removemos o preço do array por que o mesmo é alterado em uma tabela separada;

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

    /**
     * Função para deletar o produto
     * 
     */
    public function destroy($id)
    {
        $db = new db();
        $result = $db->delete($id, 'produtos');
        
        if($result){
            $db = new db();
            $preco = json_decode($db->getRow('precos', '*', "WHERE id_prod = " . $id), true);

            $deletarPreco = $db->delete($preco['id'], 'precos');

            if($deletarPreco){
                return json_encode(['type' => 'success', 'msg'=> 'Produto removido.']);
            }else{
                return json_encode(['type' => 'error', 'msg'=> 'Falha ao remover preço do produto.']);
            }

            
        }else{
            return json_encode(['type' => 'error', 'msg'=> 'Falha ao remover produto.']);
        }
    }

}

?>