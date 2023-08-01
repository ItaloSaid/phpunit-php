<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Event\Runtime\PHP;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    /** @var Avaliador  */
    private $leiloeiro;
    protected function setUp(): void
    {
        echo "Executando setUp" . PHP_EOL;
        $this->leiloeiro =  new Avaliador();
    }
    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        //Act - When           ---------    Executo o codigo a ser testado
        $this->leiloeiro -> avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        //Assert - Then       --------   Verifico se a saida e a esperada
        $this->assertEquals(2500,$maiorValor);

    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        //Act - When           ---------    Executo o codigo a ser testado
        $this->leiloeiro -> avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        //Assert - Then       --------   Verifico se a saida e a esperada
        $this->assertEquals(1700,$menorValor);

    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemAleatoria
     */
    public function testAvaliadorDevebuscar3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();
        static::assertCount(3,$maiores);
        static::assertEquals(2500,$maiores[0]->getValor());
        static::assertEquals(2000,$maiores[1]->getValor());
        static::assertEquals(1700,$maiores[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avalir um leilão vazio');
            $leilao = new Leilao('Fusca Azul');
            $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');

        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);

    }

    /* ------------- DADOS --------------*/
    public static function leilaoEmOrdemCrescente()
    {
        echo "Criando leilao em ordem crescente". PHP_EOL;
        $leilao = new Leilao('Fiat 147 0 KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana,1700));
        $leilao ->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria,2500));

        return [
            'ordem-crescente' =>[$leilao]
        ];
    }

    public static function leilaoEmOrdemDecrescente()
    {
        echo "Criando leilao em ordem decrescente". PHP_EOL;

        $leilao = new Leilao('Fiat 147 0 KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana,1700));

        return [
            'ordem-decrescente' =>[$leilao]
        ];
    }

    public static function leilaoEmOrdemAleatoria()
    {
        echo "Criando leilao em ordem aleatoria". PHP_EOL;

        $leilao = new Leilao('Fiat 147 0 KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria,2500));
        $leilao->recebeLance(new Lance($ana,1700));

        return [
            'aleatoria' =>[$leilao]
        ];
    }

}