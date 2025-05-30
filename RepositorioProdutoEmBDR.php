<?php

require_once 'RepositorioException.php';
require_once 'RepositorioProduto.php';
require_once 'Produto.php';

class RepositorioProdutoEmBDR implements RepositorioProduto {

    private $pdo = null;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    /**@inheritDoc */
    public function listarTodos(): array {
        $produtos = [];

        try {
            $sql = 'SELECT id, nome, preco, estoque FROM produto';
            $ps = $this->pdo->prepare( $sql );
            $ps->execute();
            $dados = $ps->fetchAll( PDO::FETCH_ASSOC );

            foreach ( $dados as $d ) {
                $produtos []= new Produto(
                    $d[ 'id' ],
                    $d[ 'nome' ],
                    $d[ 'preco' ],
                    $d[ 'estoque' ]
                );
            }

            return $produtos;
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao listar produtos', (int) $e->getCode(), $e );
        }
    }

    /**@inheritDoc */
    public function obterProdutoPorId( int $id ): Produto {
        try {
            $sql = 'SELECT id, nome, preco, estoque FROM produto WHERE id = :id';
            $ps = $this->pdo->prepare( $sql );
            $ps->execute( [ 'id' => $id ] );
            $produto = $ps->fetch( PDO::FETCH_ASSOC );

            return new Produto( 
                $produto[ 'id' ],
                $produto[ 'nome' ],
                $produto[ 'preco' ],
                $produto[ 'estoque' ] );
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao obter produto', $e->getCode(), $e );
        }
    }

    /**@inheritDoc */
    public function adicionarProduto( Produto $produto ): void {
        try {
            $sql = 'INSERT INTO produto( nome, preco, estoque ) 
                        VALUES ( :nome, :preco, :estoque )';
            $ps = $this->pdo->prepare( $sql );
            $ps->execute( [
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'estoque' => $produto->estoque
            ] );
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao cadastrar produto', $e->getCode(), $e );
        }
    }

    /**@inheritDoc */
    public function atualizarProduto( Produto $produto ): void {
        try {
            $sql = 'UPDATE produto SET nome = :nome, preco = :preco, estoque = :estoque WHERE id = :id';
            $ps = $this->pdo->prepare( $sql );
            $ps->execute( [
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'estoque' => $produto->estoque,
                'id' => $produto->id
            ] );
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao atualizar produto', $e->getCode(), $e );
        }
    }
    
    /**@inheritDoc */
    public function removerProduto( int $id ): void {
        try {
            $sql = 'DELETE FROM produto WHERE id = :id';
            $ps = $this->pdo->prepare( $sql );
            $ps->execute( [ 'id' => $id ] );
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao atualizar produto', $e->getCode(), $e );
        }
    }
}

