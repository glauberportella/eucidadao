<?php

namespace AppBundle\Camara;

use Zend\Http\Client;
use Zend\Dom\Query;

use Symfony\Component\Cache\Adapter\AdapterInterface;

abstract class CamaraWebService
{
	const DEPUTADOS_ENDPOINT = 'http://www.camara.gov.br/SitCamaraWS/Deputados.asmx';
	const PROPOSICOES_ENDPOINT = 'http://www.camara.gov.br/SitCamaraWS/Proposicoes.asmx';

	/**
	 * @var Zend\Http\Client
	 */
	protected $client;
	protected $domQuery;
	protected $cache;

	public function __construct(Client $client, Query $domQuery, AdapterInterface $cacheAdapter)
	{
		$this->client = $client;
		$this->client->setOptions([
			'timeout' => 30,
		]);
		$this->domQuery = $domQuery;

		$this->cache = $cacheAdapter;
	}
}