<?php

namespace Tests\AppBundle\Camara\Services;

use Zend\Http\Client;
use Zend\Dom\Query;
use AppBundle\Camara\Services\ProposicaoService;

class ProposicaoServiceIntegrationTest extends \PHPUnit_Framework_TestCase
{
	private $service;

	public function setUp()
	{
		$this->service = new ProposicaoService(new Client(), new Query());
	}

	public function tearDown()
	{
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
}