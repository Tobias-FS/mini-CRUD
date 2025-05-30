<?php

class Produto {

    public $id = 0;
    public $nome = '';
    public $preco = 0.0;
    public $estoque = 0;

    public function __construct( $id, $nome, $preco, $estoque ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function validar() {
        $problemas = [];

        if ( ! is_numeric( $this->preco ) ) {
            $problemas []= 'O preço deve ser um numero';
        }
        if ( ! is_numeric( $this->estoque ) || $this->estoque < 0 ) {
            $problemas []= 'O estoque deve ser um numero e ser positivo';
        }

        return $problemas;
    }
}

?>