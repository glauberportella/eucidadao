<?php

namespace AppBundle\Camara\Services;

use AppBundle\Camara\CamaraWebService;
use AppBundle\Camara\Model\Deputado;
use Zend\Http\Request;
use Zend\Http\Response;

class DeputadoService extends CamaraWebService
{
	/**
	 * @return AppBundle\Camara\Model\Deputado[]
	 */
	public function obterDeputados()
	{
		$deputados = [];

		$cacheDeputados = $this->cache->getItem('camara.deputados');
		if ($cacheDeputados->isHit()) {
			return $cacheDeputados->get();
		}

		$response = $this->client
			->setUri(self::DEPUTADOS_ENDPOINT.'/ObterDeputados?')
			->setMethod(Request::METHOD_GET)
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$results = $this->domQuery->queryXpath('/deputados/deputado');
		foreach ($results as $domEl) {
			$deputado = new Deputado();
			$deputado->ideCadastro = $domEl->getElementsByTagName('ideCadastro')->item(0)->nodeValue;
			$deputado->condicao = $domEl->getElementsByTagName('condicao')->item(0)->nodeValue;
			$deputado->nome = $domEl->getElementsByTagName('nome')->item(0)->nodeValue;
			$deputado->nomeParlamentar = $domEl->getElementsByTagName('nomeParlamentar')->item(0)->nodeValue;
			$deputado->urlFoto = $domEl->getElementsByTagName('urlFoto')->item(0)->nodeValue;
			$deputado->sexo = $domEl->getElementsByTagName('sexo')->item(0)->nodeValue;
			$deputado->uf = $domEl->getElementsByTagName('uf')->item(0)->nodeValue;
			$deputado->partido = $domEl->getElementsByTagName('partido')->item(0)->nodeValue;
			$deputado->gabinete = $domEl->getElementsByTagName('gabinete')->item(0)->nodeValue;
			$deputado->anexo = $domEl->getElementsByTagName('anexo')->item(0)->nodeValue;
			$deputado->fone = $domEl->getElementsByTagName('fone')->item(0)->nodeValue;
			$deputado->email = $domEl->getElementsByTagName('email')->item(0)->nodeValue;
			$deputados[] = $deputado;
		}

		usort($deputados, function(Deputado $a, Deputado $b) {
			if ($a->nome == $b->nome) {
				return 0;
			}
			return strnatcmp($a->nome, $b->nome);
		});

		// save to cache
		$cacheDeputados->set($deputados);
		$this->cache->save($cacheDeputados);

		return $deputados;
	}

	public function obterDeputadosUf($uf)
	{
		return array_filter($this->obterDeputados(), function($deputado) use ($uf) {
			return strtolower($deputado->uf) === strtolower($uf);
		});
	}

	public function obterDetalhesDeputados()
	{

	}

	public function obterLideresBancadas()
	{

	}

	public function obterParticosCd()
	{

	}

	public function obterPartidosBlocoCd()
	{

	}
}