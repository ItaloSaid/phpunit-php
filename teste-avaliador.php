<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor\autoload.php';

//Arrange - Given     -------    Arrumo a casa para o teste
$leilao = new Leilao('Fiat 147 0 KM');

$maria = new Usuario('Maria');
$joao = new Usuario('Joao');

$leilao ->recebeLance(new Lance($joao, 200));
$leilao->recebeLance(new Lance($maria,2500));

$leiloeiro = new Avaliador();
//Act - When           ---------    Executo o codigo a ser testado
$leiloeiro -> avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

//Assert - Then       --------   Verifico se a saida e a esperada
$valorEsperado = 2500;

if($valorEsperado == $maiorValor){
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}
echo $maiorValor;