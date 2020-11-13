$(document).ready(function(){
    //realiza a filtragem
    $('#btnFilter').on('click', function(){
        loadProd(true)
    });

    //limpa o formulario de filtro e reseta a listagem
    $('#btnClear').on('click', function(){
        $('#valorFiltro').val('')
        loadProd()
    })

    //Faz a listagem dos produtos
    function loadProd(filtro = false){
        var form = new FormData();
        form.append("action", "select");

        if(filtro){
            form.append("filtro", $('#filtro').val());
            form.append("valor", $('#valorFiltro').val());
        }
        
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "http://localhost/teste-php/php/api.php",
            "method": "POST",
            "headers": {
                "cache-control": "no-cache",
            },
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": form
        }
        
        $.ajax(settings).done(function (response) {
            console.log(response)
            var data = JSON.parse(response);
            $('#listagem').empty();
            $.each(data, function(key, item){
                let html  = '<tr>\
                                <td>'+item.id+'</td>\
                                <td>'+item.nome+'</td>\
                                <td>'+item.cor+'</td>\
                                <td>'+parseFloat(item.preco).toLocaleString("pt-BR",{style: 'currency', currency: "BRL"})+'</td>\
                                <td>'+desconto(item.preco, item.cor)+'</td>\
                                <td><button class="btn btn1 btnUpdate" data-id="'+item.id+'" data-nome="'+item.nome+'" data-preco="'+item.preco+'" type="button">Editar</button> \
                                <button class="btn btn2 btnDelete" data-id="'+item.id+'" type="button">Deletar</button></td>\
                            </tr>';
                $('#listagem').append(html);
            })

            $('.btnUpdate').on('click', function(){
                editProd($(this).data('id'), $(this).data('nome'), $(this).data('preco'));
            })

            $('.btnDelete').on('click', function(){
                if (window.confirm("Você realmente deseja deletar o registro?")) { 
                    deleteProd($(this).data('id'))
                }
                return true;
            })

        });
    }
    loadProd();

    //calcula o desconto dos produtos
    function desconto(value, color){
        switch(color){
            case 'azul':
                return  parseFloat(( value - (0.2 * value))).toLocaleString("pt-BR",{style: 'currency', currency: "BRL"}) + " (20% off) "
            break;
            case 'vermelho':
                if(value > 50.0){
                    return  parseFloat(( value - (0.25 * value))).toLocaleString("pt-BR",{style: 'currency', currency: "BRL"}) + " (25% off) "
                }
                return  parseFloat(( value - (0.2 * value))).toLocaleString("pt-BR",{style: 'currency', currency: "BRL"}) + " (20% off) "
            break;
            case 'amarelo':
                return  parseFloat(( value - (0.1 * value))).toLocaleString("pt-BR",{style: 'currency', currency: "BRL"}) + " (10% off) "
            break;
            default:
                return 0.00

        }
    }

    //realiza a inserção do produto
    function insertProd(){
        var form = new FormData();
        form.append("action", "insert");
        form.append("nome", $('#nome').val());
        form.append("cor", $('#cor').val());
        form.append("preco", $('#preco').val());

        var settings = {
        "async": true,
            "crossDomain": true,
            "url": "http://localhost/teste-php/php/api.php",
            "method": "POST",
            "headers": {
                "cache-control": "no-cache",
            },
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": form
        }

        $.ajax(settings).done(function (response) {
            console.log(response);
            $('#idProd').val('');
            $('#nome').val('');
            $('#preco').val('');
            loadProd()
        });
    }

    $('#btnInsert').on('click', insertProd);

    //Carrega a visualização para a edição do produto
    function editProd(id, nome, preco){
        $('#idProd').val(id);
        $('#nome').val(nome);
        $('#preco').val(preco);

        $('#titleSection').html('Alterar Produto');

        $('#btnInsert').css('display', 'none');
        $('#cor').css('display', 'none');
        $('#corLabel').css('display', 'none');
        $('#btnUpdate').css('display', 'block');
        $('#btnCancel').css('display', 'block');
    }

    //atualizar um produto
    function updateProd(){
        var form = new FormData();
        form.append("action", "update");
        form.append("id", $('#idProd').val());
        form.append("nome", $('#nome').val());
        form.append("preco", $('#preco').val());

        var settings = {
        "async": true,
        "crossDomain": true,
        "url": "http://localhost/teste-php/php/api.php",
        "method": "POST",
        "headers": {
            "cache-control": "no-cache",
        },
        "processData": false,
        "contentType": false,
        "mimeType": "multipart/form-data",
        "data": form
        }

        $.ajax(settings).done(function (response) {
            console.log(response);
            loadProd()
            resetForm()
        });
    }

    $('#btnUpdate').on('click', updateProd)
    
    //remove um produto
    function deleteProd(id){
        var form = new FormData();
        form.append("action", "delete");
        form.append("id", id);

        var settings = {
        "async": true,
        "crossDomain": true,
        "url": "http://localhost/teste-php/php/api.php",
        "method": "POST",
        "headers": {
            "cache-control": "no-cache",
        },
        "processData": false,
        "contentType": false,
        "mimeType": "multipart/form-data",
        "data": form
        }

        $.ajax(settings).done(function (response) {
            console.log(response);
            loadProd()
            resetForm()
        });
    }


    //limpa e reseta o formulario
    function resetForm(){
        $('#idProd').val('');
        $('#nome').val('');
        $('#preco').val('');

        $('#titleSection').html('Adicionar Produto');

        $('#btnInsert').css('display', 'block');
        $('#cor').css('display', 'block');
        $('#corLabel').css('display', 'block');
        $('#btnUpdate').css('display', 'none');
        $('#btnCancel').css('display', 'none');
    }

    $('#btnCancel').on('click', function(){
        resetForm()
    });
    

})