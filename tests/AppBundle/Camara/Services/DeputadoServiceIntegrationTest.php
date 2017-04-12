<?php

namespace Tests\AppBundle\Camara\Services;

use Zend\Http\Client;
use Zend\Dom\Query;
use AppBundle\Camara\Services\DeputadoService;

class DeputadoServiceIntegrationTest extends \PHPUnit_Framework_TestCase
{
	private $service;

	public function setUp()
	{
		$this->service = new DeputadoService(new Client(), new Query());
	}

	public function tearDown()
	{
		$this->service = null;
	}

	public function testServiceInstance()
	{
		$this->assertInstanceOf('AppBundle\Camara\Services\DeputadoService', $this->service);
	}

	public function testObterDeputados()
	{
		$results = $this->service->obterDeputados();
		$this->assertTrue(count($results) > 0);
		$this->assertInstanceOf('AppBundle\Camara\Model\Deputado', $results[0]);
	}

	public function testObterDeputadosUf()
	{
		$results = $this->service->obterDeputadosUf('mg');
		$this->assertTrue(count($results) > 0);
		$results = $this->service->obterDeputadosUf('Mg');
		$this->assertTrue(count($results) > 0);
		$results = $this->service->obterDeputadosUf('MG');
		$this->assertTrue(count($results) > 0);
	}
}