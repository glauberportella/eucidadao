<?php

namespace AppBundle\Camara\Model;

/**
 * @property ideCadastro		Int	ID do parlamentar
 * @property condicao			String	Retorna se o deputado e Titular ou suplente
 * @property nome				String	Nome civil do parlamentar
 * @property nomeParlamentar	String	Nome de tratamento do parlamentar
 * @property urlFoto			String	URL para a foto do parlamentar
 * @property sexo				String	Sexo (masculino ou feminino)
 * @property uf					String	Unidade da Federação de representação do parlamentar
 * @property partido			String	Sigla do partido que o parlamentar representa
 * @property gabinete			String	Numero do Gabinete do parlamentar
 * @property anexo				String	Anexo (prédio) onde o gabinete está localizado
 * @property fone				String	Numero do telefone do gabinete
 * @property email				String	Email institucional do parlamentar
 */
class Deputado extends ServiceModel
{

}