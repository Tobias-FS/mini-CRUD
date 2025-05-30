<?php

require_once 'RepositorioException.php';
require_once 'Produto.php';

interface RepositorioProduto {

    /**
     * Retorna todos os produtos
     * 
     * @return Produto[]
     * @throws RepositorioException
     */
    function listarTodos(): array;

    /**
     * Retorna um produto especifico
     * 
     * @param int $id
     * @return Produto $produto
     * @throws RepositorioException
     */
    function obterProdutoPorId( int $id ): Produto;

    /**
     * Adiciona um produto
     * 
     * @param Produto $produto
     * @throws RepositorioException
     */
    function adicionarProduto( Produto $produto ): void;

    /**
     * Atualiza um produto
     * 
     * @param Produto $produto
     * @throws RepositorioException
     */
    function atualizarProduto( Produto $produto ): Produto;

    /**
     * Remove um produto
     * 
     * @param int $id
     * @throws RepositorioException
     */
    function removerProduto( int $id ): void;
}

?>