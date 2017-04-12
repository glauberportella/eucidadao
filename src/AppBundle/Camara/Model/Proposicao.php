<?php

namespace AppBundle\Camara\Model;

/**
 * @property tipo				String	Tipo da proposicao
 * @property numero				Int	Numero da proposicao
 * @property ano				Int	Ano de apresentação da proposição
 * @property idProposicao		Int	ID da proposição
 * @property ementa				String		Ementa da proposição
 * @property explicacaoEmenta	String	Explicação da ementa da proposição
 * @property autor				String	Nome do autor da proposição
 * @property dataApresentacao	Date	Data em que a propsoição foi apresentada na Câmara dos Deputados
 * @property regimeTramitacao	String	Regime de tramitação da Proposição (ex: tramitação ordinária, urgência, etc)
 * @property ultimoDespacho		String	Ultimo despacho proferido para a proposição
 * @property dataUltimoDespacho	Date	Data do Ultimo despacho proferido para a proposição
 * @property apreciacao			String	Forma de apreciação da proposição na Câmara dos Deputados (conclusiva das comissões ou de apreciação do Plenário)
 * @property indexacao			String	Indexação (palavras-chave) associada à proposição
 * @property situacao			String	Descrição da situação da proposição na Câmara dos Deputados
 * @property linkInteiroTeor	String	URL contendo o link para o inteiro teor da proposição
 */
class Proposicao extends ServiceModel
{

}