<?php

namespace AppBundle\Camara\Services;

use AppBundle\Camara\CamaraWebService;
use AppBundle\Camara\Model\Proposicao;
use AppBundle\Camara\Model\SiglaTipoProposicao;
use AppBundle\Camara\Model\Votacao;
use Zend\Http\Request;
use Zend\Http\Response;

class ProposicaoService extends CamaraWebService
{
	/**
	 * @param  array  $params Array de parametros:
	 *							Sigla String - Obrigatorio se ParteNomeAutor não for preenchido. Sigla do tipo de proposição
	 *							Numero	Int(Opcional)	Numero da proposição
	 *							Ano	Int(Obrigatorio se ParteNomeAutor não for preenchido)	Ano da proposição
	 *							datApresentacaoIni	Date(Opcional)	Menor data desejada para a data de apresentação da proposição.
	 *						  						Formato: DD/MM/AAAA
	 *							datApresentacaoFim	Date(Opcional)	Maior data desejada para a data de apresentação da proposição
	 *												Formato: DD/MM/AAAA
	 *							IdTipoAutor	Int(Optional)	Identificador do tipo de órgão autor da proposição, como obtido na chamada ao ListarTiposOrgao
	 *							ParteNomeAutor	String(Optional)	Parte do nome do autor(5 ou + caracteres) da proposição.
	 *							SiglaPartidoAutor	String(Optional)	Sigla do partido do autor da proposição
	 *							SiglaUfAutor	String(Optional)	UF de representação do autor da proposição
	 *							GeneroAutor	String(Optional)	Gênero do autor<BR>M - Masculino; F - Feminino; Default - Todos
	 *							IdSituacaoProposicao	Int(Opcional)	ID da situação da proposição
	 *							IdOrgaoSituacaoProposicao	Int(Opcional)	ID do órgão de referência da situação da proposição
	 *							EmTramitacao	int(Opcional)	Indicador da situação de tramitação da proposição<BR>1 - Em Tramitação no Congresso; 2- Tramitação Encerrada no Congresso; Default - Todas
	 * @return AppBundle\Camara\Model\Proposicao[]
	 */
	public function obterProposicoes(array $params)
	{
		//http://www.camara.gov.br/SitCamaraWS/Proposicoes.asmx/ListarProposicoes?sigla=PL&numero=&ano=2011&datApresentacaoIni=14/11/2011&datApresentacaoFim=16/11/2011&parteNomeAutor=&idTipoAutor=&siglaPartidoAutor=&siglaUFAutor=&generoAutor=&codEstado=&codOrgaoEstado=&emTramitacao=
		$params = [
			'sigla' => isset($params['sigla']) ? $params['sigla'] : '',
			'numero' => isset($params['numero']) ? $params['numero'] : '',
			'ano' => isset($params['ano']) ? $params['ano'] : '',
			'datApresentacaoIni' => isset($params['datApresentacaoIni']) ? $params['datApresentacaoIni'] : '',
			'datApresentacaoFim' => isset($params['datApresentacaoFim']) ? $params['datApresentacaoFim'] : '',
			'idTipoAutor' => isset($params['idTipoAutor']) ? $params['idTipoAutor'] : '',
			'parteNomeAutor' => isset($params['parteNomeAutor']) ? $params['parteNomeAutor'] : '',
			'siglaPartidoAutor' => isset($params['siglaPartidoAutor']) ? $params['siglaPartidoAutor'] : '',
			'siglaUfAutor' => isset($params['siglaUfAutor']) ? $params['siglaUfAutor'] : '',
			'generoAutor' => isset($params['generoAutor']) ? $params['generoAutor'] : '',
			'codEstado' => isset($params['codEstado']) ? $params['codEstado'] : '',
			'idSituacaoProposicao' => isset($params['idSituacaoProposicao']) ? $params['idSituacaoProposicao'] : '',
			'idOrgaoSituacaoProposicao' => isset($params['idOrgaoSituacaoProposicao']) ? $params['idOrgaoSituacaoProposicao'] : '',
			'codOrgaoEstado' => isset($params['codOrgaoEstado']) ? $params['codOrgaoEstado'] : '',
			'emTramitacao' => isset($params['emTramitacao']) ? $params['emTramitacao'] : '',
		];

		$proposicoes = [];

		$response = $this->client
			->setUri(self::PROPOSICOES_ENDPOINT.'/ListarProposicoes')
			->setMethod(Request::METHOD_GET)
			->setParameterGet($params)
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$results = $this->domQuery->queryXpath('/proposicoes/proposicao');
		foreach ($results as $domEl) {
			$proposicao = new Proposicao();
			$proposicao->id = $domEl->getElementsByTagName('id')->item(0)->nodeValue;
			$proposicao->nome = $domEl->getElementsByTagName('nome')->item(0)->nodeValue;
			if ($tipoProposicao = $domEl->getElementsByTagName('tipoProposicao')->item(0)) {
				$proposicao->tipoProposicao = new \stdClass;
				$proposicao->tipoProposicao->id = $tipoProposicao->getElementsByTagName('id')->item(0)->nodeValue;
				$proposicao->tipoProposicao->sigla = $tipoProposicao->getElementsByTagName('sigla')->item(0)->nodeValue;
				$proposicao->tipoProposicao->nome = $tipoProposicao->getElementsByTagName('nome')->item(0)->nodeValue;
			}
			$proposicao->numero = $domEl->getElementsByTagName('numero')->item(0)->nodeValue;
			$proposicao->ano = $domEl->getElementsByTagName('ano')->item(0)->nodeValue;
			if ($orgaoNumerador = $domEl->getElementsByTagName('orgaoNumerador')->item(0)) {
				$proposicao->orgaoNumerador = new \stdClass;
				$proposicao->orgaoNumerador->id = $orgaoNumerador->getElementsByTagName('id')->item(0)->nodeValue;
				$proposicao->orgaoNumerador->sigla = $orgaoNumerador->getElementsByTagName('sigla')->item(0)->nodeValue;
				$proposicao->orgaoNumerador->nome = $orgaoNumerador->getElementsByTagName('nome')->item(0)->nodeValue;
			}
			$proposicao->datApresentacao = $domEl->getElementsByTagName('datApresentacao')->item(0)->nodeValue;
			$proposicao->txtEmenta = $domEl->getElementsByTagName('txtEmenta')->item(0)->nodeValue;
			$proposicao->txtExplicacaoEmenta = $domEl->getElementsByTagName('txtExplicacaoEmenta')->item(0)->nodeValue;
			if ($regime = $domEl->getElementsByTagName('regime')->item(0)) {
				$proposicao->regime = new \stdClass;
				$proposicao->regime->codRegime = $regime->getElementsByTagName('codRegime')->item(0)->nodeValue;
				$proposicao->regime->txtRegime = $regime->getElementsByTagName('txtRegime')->item(0)->nodeValue;
			}
			if ($apreciacao = $domEl->getElementsByTagName('apreciacao')->item(0)) {
				$proposicao->apreciacao = new \stdClass;
				$proposicao->apreciacao->id = $apreciacao->getElementsByTagName('id')->item(0)->nodeValue;
				$proposicao->apreciacao->txtApreciacao = $apreciacao->getElementsByTagName('txtApreciacao')->item(0)->nodeValue;
			}
			if ($autor1 = $domEl->getElementsByTagName('autor1')->item(0)) {
				$proposicao->autor1 = new \stdClass;
				$proposicao->autor1->txtNomeAutor = $autor1->getElementsByTagName('txtNomeAutor')->item(0)->nodeValue;
				$proposicao->autor1->idecadastro = $autor1->getElementsByTagName('idecadastro')->item(0)->nodeValue;
				$proposicao->autor1->codPartido = $autor1->getElementsByTagName('codPartido')->item(0)->nodeValue;
				$proposicao->autor1->txtSiglaPartido = $autor1->getElementsByTagName('txtSiglaPartido')->item(0)->nodeValue;
				$proposicao->autor1->txtSiglaUf = $autor1->getElementsByTagName('txtSiglaUF')->item(0)->nodeValue;
			}
			$proposicao->qtdAutores = $domEl->getElementsByTagName('qtdAutores')->item(0)->nodeValue;
			if ($ultimoDespacho = $domEl->getElementsByTagName('ultimoDespacho')->item(0)) {
				$proposicao->ultimoDespacho = new \stdClass;
				$proposicao->ultimoDespacho->datDespacho = $ultimoDespacho->getElementsByTagName('datDespacho')->item(0)->nodeValue;
				$proposicao->ultimoDespacho->txtDespacho = $ultimoDespacho->getElementsByTagName('txtDespacho')->item(0)->nodeValue;
			}
			if ($situacao = $domEl->getElementsByTagName('situacao')->item(0)) {
				$proposicao->situacao = new \stdClass;
				$proposicao->situacao->id = $situacao->getElementsByTagName('id')->item(0)->nodeValue;
				$proposicao->situacao->descricao = $situacao->getElementsByTagName('descricao')->item(0)->nodeValue;
				if ($orgao = $situacao->getElementsByTagName('orgao')->item(0)) {
					$proposicao->situacao->orgao = new \stdClass;
					$proposicao->situacao->orgao->codOrgaoEstado = $orgao->getElementsByTagName('codOrgaoEstado')->item(0)->nodeValue;
					$proposicao->situacao->orgao->siglaOrgaoEstado = $orgao->getElementsByTagName('siglaOrgaoEstado')->item(0)->nodeValue;
				}
				if ($principal = $situacao->getElementsByTagName('principal')->item(0)) {
					$proposicao->situacao->principal = new \stdClass;
					$proposicao->situacao->principal->codProposicaoPrincipal = $principal->getElementsByTagName('codProposicaoPrincipal')->item(0)->nodeValue;
					$proposicao->situacao->principal->proposicaoPrincipal = $principal->getElementsByTagName('proposicaoPrincipal')->item(0)->nodeValue;
				}
			}
			$proposicao->indGenero = $domEl->getElementsByTagName('indGenero')->item(0)->nodeValue;
			$proposicao->qtdOrgaosComEstado = $domEl->getElementsByTagName('qtdOrgaosComEstado')->item(0)->nodeValue;
			$proposicoes[] = $proposicao;
		}

		return $proposicoes;
	}

	public function obterProposicoesAno($tipo, $ano)
	{
		$proposicoes = [];

		$cache = $this->cache->getItem(sprintf('camara.proposicoes.%s_%d', strtolower(trim($tipo)), $ano));
		if ($cache->isHit()) {
			return $cache->get();
		}

		$proposicoes = $this->obterProposicoes([
			'sigla' => $tipo,
			'ano' => $ano,
		]);

		// save to cache
		$cache->set($proposicoes);
		$this->cache->save($cache);

		return $proposicoes;
	}

	public function obterProposicao($tipo, $numero, $ano)
	{
		$proposicao = null;

		$response = $this->client
			->setUri(self::PROPOSICOES_ENDPOINT.'/ObterProposicao')
			->setMethod(Request::METHOD_GET)
			->setParameterGet([
				'tipo' => $tipo,
				'numero' => $numero,
				'ano' => $ano,
			])
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$result = $this->domQuery->queryXpath('/proposicao');
		if (!count($result)) {
			return null;
		}

		$domEl = $result[0];

		$proposicao = new Proposicao();
		$proposicao->tipo = $domEl->getAttribute('tipo');
		$proposicao->numero = $domEl->getAttribute('numero');
		$proposicao->ano = $domEl->getAttribute('ano');
		$proposicao->nomeProposicao = $domEl->getElementsByTagName('nomeProposicao')->item(0)->nodeValue;
		$proposicao->idProposicaoPrincipal = $domEl->getElementsByTagName('idProposicaoPrincipal')->item(0)->nodeValue;
		$proposicao->nomeProposicaoOrigem = $domEl->getElementsByTagName('nomeProposicaoOrigem')->item(0)->nodeValue;
		$proposicao->tipoProposicao = $domEl->getElementsByTagName('tipoProposicao')->item(0)->nodeValue;
		$proposicao->tema = $domEl->getElementsByTagName('tema')->item(0)->nodeValue;
		$proposicao->idProposicao = $domEl->getElementsByTagName('idProposicao')->item(0)->nodeValue;
		$proposicao->ementa = $domEl->getElementsByTagName('Ementa')->item(0)->nodeValue;
		$proposicao->explicacaoEmenta = $domEl->getElementsByTagName('ExplicacaoEmenta')->item(0)->nodeValue;
		$proposicao->autor = $domEl->getElementsByTagName('Autor')->item(0)->nodeValue;
		$proposicao->dataApresentacao = $domEl->getElementsByTagName('DataApresentacao')->item(0)->nodeValue;
		$proposicao->regimeTramitacao = $domEl->getElementsByTagName('RegimeTramitacao')->item(0)->nodeValue;
		$proposicao->ultimoDespacho = $domEl->getElementsByTagName('UltimoDespacho')->item(0)->nodeValue;
		$proposicao->dataUltimoDespacho = $domEl->getElementsByTagName('UltimoDespacho')->item(0)->getAttribute('Data');
		$proposicao->apreciacao = $domEl->getElementsByTagName('Apreciacao')->item(0)->nodeValue;
		$proposicao->indexacao = $domEl->getElementsByTagName('Indexacao')->item(0)->nodeValue;
		$proposicao->situacao = $domEl->getElementsByTagName('Situacao')->item(0)->nodeValue;
		$proposicao->linkInteiroTeor = $domEl->getElementsByTagName('LinkInteiroTeor')->item(0)->nodeValue;

		return $proposicao;
	}

	public function obterVotacaoProposicao($tipo, $numero, $ano)
	{
		$votacoes = [];

		$response = $this->client
			->setUri(self::PROPOSICOES_ENDPOINT.'/ObterVotacaoProposicao')
			->setMethod(Request::METHOD_GET)
			->setParameterGet([
				'tipo' => $tipo,
				'numero' => $numero,
				'ano' => $ano,
			])
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$results = $this->domQuery->queryXpath('/proposicao/Votacoes/Votacao');
		foreach ($results as $domEl) {
			$votacao = new Votacao();
			$votacao->resumo = $domEl->getAttribute('Resumo');
			$votacao->data = $domEl->getAttribute('Data');
			$votacao->hora = $domEl->getAttribute('Hora');
			$votacao->objVotacao = $domEl->getAttribute('ObjVotacao');
			$votacao->codSessao = $domEl->getAttribute('codSessao');
			if ($orientacaoBancada = $domEl->getElementsByTagName('orientacaoBancada')->item(0)) {
				$votacao->orientacaoBancada = [];
				foreach ($orientacaoBancada as $bancadaEl) {
					$bancada = new \stdClass;
					$bancada->sigla = $bancadaEl->getAttribute('Sigla');
					$bancada->orientacao = $bancadaEl->getAttribute('orientacao');
					$votacao->orientacaoBancada[] = $bancada;
				}
			}
			if ($votos = $domEl->getElementsByTagName('votos')->item(0)) {
				$votacaoVotos = [];
				foreach ($votos->getElementsByTagName('Deputado') as $deputadoEl) {
					$voto = new \stdClass;
					$voto->nome = $deputadoEl->getAttribute('Nome');
					$voto->ideCadastro = $deputadoEl->getAttribute('ideCadastro');
					$voto->partido = $deputadoEl->getAttribute('Partido');
					$voto->uf = $deputadoEl->getAttribute('UF');
					$voto->voto = $deputadoEl->getAttribute('Voto');
					$votacaoVotos[] = $voto;
				}
				$votacao->votos = $votacaoVotos;
			}
			$votacoes[] = $votacao;
		}

		return $votacoes;
	}

	public function obterSiglasTipoProposicao()
	{
		$siglas = [];

		$cache = $this->cache->getItem('camara.proposicoes.siglas_tipo');
		if ($cache->isHit()) {
			return $cache->get();
		}

		$response = $this->client
			->setUri(self::PROPOSICOES_ENDPOINT.'/ListarSiglasTipoProposicao')
			->setMethod(Request::METHOD_GET)
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$results = $this->domQuery->queryXpath('/siglas/sigla');
		foreach ($results as $domEl) {
			$sigla = new SiglaTipoProposicao();
			$sigla->tipoSigla = trim($domEl->getAttribute('tipoSigla'));
			$sigla->descricao = trim($domEl->getAttribute('descricao'));
			$sigla->ativa = strtolower((string)$domEl->getAttribute('ativa')) == strtolower('True');
			$sigla->genero = $domEl->getAttribute('genero');
			$siglas[] = $sigla;
		}

		usort($siglas, function(SiglaTipoProposicao $a, SiglaTipoProposicao $b) {
			if ($a->tipoSigla == $b->tipoSigla) {
				return 0;
			}
			return strnatcmp($a->tipoSigla, $b->tipoSigla);
		});

		// save to cache
		$cache->set($siglas);
		$this->cache->save($cache);

		return $siglas;
	}

	/**
	 * Obtem proposicoes que tramitarão em um periodo
	 *
	 * @param  string $dtinicio String representando data no formato dd/mm/aaaa
	 * @param  string $dtfim    String representando data no formato dd/mm/aaaa
	 * @return array
	 */
	public function listarProposicoesTramitadasNoPeriodo($dtinicio, $dtfim)
	{
		$proposicoes = [];

		$cache = $this->cache->getItem('camara.proposicoes.periodo'.str_replace('/', '_', $dtinicio).'-'.str_replace('/', '_', $dtfim));
		if ($cache->isHit()) {
			return $cache->get();
		}

		$response = $this->client
			->setUri(self::PROPOSICOES_ENDPOINT.'/ListarProposicoesTramitadasNoPeriodo')
			->setMethod(Request::METHOD_GET)
			->setParameterGet([
				'dtInicio' => $dtinicio,
				'dtFim' => $dtfim,
			])
			->send();

		if ($response->getStatusCode() != Response::STATUS_CODE_200) {
			throw new \Exception('Response error '.$response->getReasonPhrase());
		}

		$this->domQuery->setDocumentXml($response->getBody());
		$results = $this->domQuery->queryXpath('/proposicoes/proposicao');

		foreach ($results as $domEl) {
			$proposicao = $this->obterProposicao(
				$domEl->getElementsByTagName('tipoProposicao')->item(0)->nodeValue,
				$domEl->getElementsByTagName('numero')->item(0)->nodeValue,
				$domEl->getElementsByTagName('ano')->item(0)->nodeValue
			);
			$proposicoes[] = $proposicao;
		}

		// save to cache
		$cache->set($proposicoes);
		$this->cache->save($cache);

		return $proposicoes;
	}

	/**
	 * Retorna datas inicio e fim da próxima semana (contados a partir do dia da chamada ao metodo)
	 * @return array Array com as chaves 'inicio' e 'fim' contendo objeto DateTime das datas
	 */
	public function getPeriodoUtilProximaSemana(\DateTime $dataBase)
	{
		$diaSemanaHoje = $dataBase->format('N');

		$diasAdicionar = 1;
		switch ($diaSemanaHoje) {
			case 1: // segunda
				$diasAdicionar = 7;
				break;
			case 2: // terca
				$diasAdicionar = 6;
				break;
			case 3: // quarta
				$diasAdicionar = 5;
				break;
			case 4: // quinta
				$diasAdicionar = 4;
				break;
			case 5: // sexta
				$diasAdicionar = 3;
				break;
			case 6: // sabado
				$diasAdicionar = 2;
				break;
			default: // domingo
				$diasAdicionar = 1;
		}

		$inicio = new \DateTime($dataBase->format(\DateTime::ISO8601));
		$inicio->add(new \DateInterval("P{$diasAdicionar}D"));
		$fim = new \DateTime($inicio->format(\DateTime::ISO8601));
		$fim->add(new \DateInterval("P4D"));

		return [
			'inicio' => $inicio,
			'fim' => $fim,
		];
	}
}
