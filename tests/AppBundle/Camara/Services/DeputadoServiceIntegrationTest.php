<?php

namespace Tests\AppBundle\Camara\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeputadoServiceIntegrationTest extends KernelTestCase
{
	private $service;

	public function setUp()
	{
		self::bootKernel();
		$this->service = static::$kernel->getContainer()->get('camara_deputado_service');
	}

	public function tearDown()
	{
		parent::tearDown();
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
