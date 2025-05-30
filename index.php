<?php

require_once 'RepositorioProdutoEmBDR.php';
require_once 'conexao.php';

$pdo = null;

try {
    $pdo = criarConexao();
} catch ( PDOException $e ) {
    echo $e->getMessage();
}
$repositorio = new RepositorioProdutoEmBDR( $pdo );

$metodo = $_SERVER[ 'REQUEST_METHOD' ];
$url = str_replace(  // "/projeto1/contatos" => "/contatos"
    dirname( $_SERVER[ 'PHP_SELF' ] ), // "/projeto1"
    '',
    $_SERVER[ 'REQUEST_URI' ]
);
$casamentos = [];
$regex = '/^\/produtos\/?$/i';
$regexId = '/^\/produtos\/([0-9]+)\/?$/i';

if ( $metodo == 'GET' && preg_match( $regex, $url ) ) {
    $produtos = $repositorio->listarTodos();
    header( 'Content-Type: application/json' );
    echo json_encode( $produtos );
} else if ( $metodo == 'GET' && preg_match( $regexId, $url, $casamentos ) ) {
    [ , $id ] = $casamentos;

    $produtos = $repositorio->obterProdutoPorId( $id );
    header( 'Content-Type: application/json' );
    echo json_encode( $produtos );
} else if ( $metodo == 'POST' && preg_match( $regex, $url ) ) {  
    $dados = obterDadosProduto();
    validarDados( $dados );
    $repositorio->adicionarProduto( new Produto( 
        0,
        $dados[ 'nome' ],
        $dados[ 'preco' ],
        $dados[ 'estoque' ]
    ) );
    http_response_code(201);
    echo json_encode( [ 'mensagem' => 'Produto criado com sucesso' ] );
} else if ( $metodo == 'PUT' && preg_match( $regex, $url ) ) {
    $dados = obterDadosProduto();
    validarDados( $dados );
    $repositorio->atualizarProduto( new Produto( 
        $dados[ 'id' ],
        $dados[ 'nome' ],
        $dados[ 'preco' ],
        $dados[ 'estoque' ]
     ) );
} else if ( $metodo == 'DELETE' && preg_match( $regexId, $url, $casamentos ) ) {
    [ , $id ] = $casamentos;
    $repositorio->removerProduto( $id );
}

function obterDadosProduto() {
    $texto = file_get_contents( 'php://input' );
    $dados = (array) json_decode( $texto );
    return $dados;
}

function validarDados( $dados ) {
    $problemas = [];

    if ( ! isset( $dados[ 'nome' ], $dados[ 'preco' ], $dados[ 'estoque' ] ) ) {
        $problemas []= 'Campos "nome", "preco" e "estoque" são obrigatórios';
    }

    if ( ! is_string( $dados[ 'nome' ] ) || strlen( $dados['nome'] ) < 2) {
        $problemas []=  'Nome inválido';
    }

    if ( ! is_numeric( $dados[ 'preco' ] ) || $dados[ 'preco' ] < 0 ) {
        $problemas []= 'Preço deve ser um número positivo';
    }

    if ( ! is_numeric( $dados[ 'estoque' ] ) || $dados[ 'estoque' ] < 0) {
         $problemas []= 'Estoque deve ser um número positivo';
    }

    if ( count( $problemas ) > 0 ) {
        http_response_code(400);
        header( 'Content-Type: application/json' );
        echo json_encode( $problemas );
        die();
    }

}

?>