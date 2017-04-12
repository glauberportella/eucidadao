<?php

namespace AppBundle\Camara;

use Zend\Http\Client;
use Zend\Dom\Query;

abstract class CamaraWebService
{
	const DEPUTADOS_ENDPOINT = 'http://www.camara.gov.br/SitCamaraWS/Deputados.asmx';
	const PROPOSICOES_ENDPOINT = 'http://www.camara.gov.br/SitCamaraWS/Proposicoes.asmx';

	/**
	 * @var Zend\Http\Client
	 */
	protected $client;
	protected $domQuery;

	public function __construct(Client $client, Query $domQuery)
	{
		$this->client = $client;
		$this->client->setOptions([
			'timeout' => 30,
		]);
		$this->domQuery = $domQuery;
	}
}