<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor dois lances consecutivos');
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana,1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    }

    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Usuário não pode propor mais de 5 lances');
        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao,1000));
        $leilao->recebeLance(new Lance($maria,1500));
        $leilao->recebeLance(new Lance($joao,2000));
        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($joao,3000));
        $leilao->recebeLance(new Lance($maria,3500));
        $leilao->recebeLance(new Lance($joao,4000));
        $leilao->recebeLance(new Lance($maria,4500));
        $leilao->recebeLance(new Lance($joao,5000));
        $leilao->recebeLance(new Lance($maria,5500));

        $leilao->recebeLance(new Lance($joao,6000));
    }
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoDeveReceberLances(int $qtdLances, Leilao $leilao, array $valores)
    {
        static ::assertCount($qtdLances,$leilao->getLances());
        foreach ($valores as $i => $valorEsperado){
            static ::assertEquals($valorEsperado,$leilao->getLances()[$i]->getValor());
        }
    }

    public static function geraLances()
    {
        $joao = new Usuario('Joao');
        $maria = new Usuario('Maria');

        $leilaoComDoisLances = new Leilao('Fiat 147 0KM');
        $leilaoComDoisLances->recebeLance(new Lance($joao, 1000));
        $leilaoComDoisLances->recebeLance(new Lance($maria, 2000));

        $leilaoComUmLance = new Leilao('FFusca 1972 0KM');
        $leilaoComUmLance->recebeLance(new Lance($maria, 5000));

        return[
            '2-lances' => [2, $leilaoComDoisLances, [1000, 2000]],
            '1-lance' => [1, $leilaoComUmLance, [5000]]
        ];

    }
}