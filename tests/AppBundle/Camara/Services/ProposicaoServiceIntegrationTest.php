<?php

namespace Tests\AppBundle\Camara\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProposicaoServiceIntegrationTest extends KernelTestCase
{
	private $service;

	public function setUp()
	{
		self::bootKernel();
		$this->service = static::$kernel->getContainer()->get('camara_proposicao_service');
	}

	public function tearDown()
	{
		parent::tearDown();
		$this->service = null;
	}

	public function testServiceInstance()
	{
		$this->assertInstanceOf('AppBundle\Camara\Services\ProposicaoService', $this->service);
	}

	public function testObterProposicao()
	{
		$proposicao = $this->service->obterProposicao('PL', 3962, 2008);
		$this->assertInstanceOf('AppBundle\Camara\Model\Proposicao', $proposicao);
	}

	public function testObterProposicoes()
	{
		$proposicoes = $this->service->obterProposicoes([
			'sigla' => 'PL',
			'ano' => 2011,
			'datApresentacaoIni' => '14/11/2011',
			'datApresentacaoFim' => '16/11/2011',
		]);
		$this->assertTrue(count($proposicoes) > 0);
	}

	public function testObterVotacaoProposicao()
	{
		$votacoes = $this->service->obterVotacaoProposicao('PL', 1992, 2007);
		$this->assertTrue(count($votacoes) > 0);
	}

	public function testGetPeriodoUtilProximaSemana()
	{
		$segunda = new \DateTime('2017-04-24');
		$terca = new \DateTime('2017-04-25');
		$quarta = new \DateTime('2017-04-26');
		$quinta = new \DateTime('2017-04-27');
		$sexta = new \DateTime('2017-04-28');
		$sabado = new \DateTime('2017-04-29');
		$domingo = new \DateTime('2017-04-30');

		// expects 2017-05-01 a 2017-05-05
		$periodo = $this->service->getPeriodoUtilProximaSemana($segunda);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($terca);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($quarta);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($quinta);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($sexta);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($sabado);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));

		$periodo = $this->service->getPeriodoUtilProximaSemana($domingo);
		$this->assertEquals('2017-05-01', $periodo['inicio']->format('Y-m-d'));
		$this->assertEquals('2017-05-05', $periodo['fim']->format('Y-m-d'));
	}
}
