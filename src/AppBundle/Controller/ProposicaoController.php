<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

use AppBundle\Form\FiltroProposicaoType;

class ProposicaoController extends Controller
{
    /**
     * @Route("/proposicoes", name="proposicoes")
     */
    public function listaAction(Request $request)
    {
        $service = $this->container->get('camara_proposicao_service');
        $filtroForm = $this->createForm(FiltroProposicaoType::class, null, [
            'proposicao_service' => $service,
        ]);
        
        $proposicoes = [];

        $proposicoesAno = $service->obterProposicoesAno('PL', date('Y'));

        /*
        // aplica filtros
        // @TODO jogar filtros no Service
        $filtroForm->handleRequest($request);
        if ($filtroForm->isSubmitted() && $filtroForm->isValid()) {
            $filtros = $filtroForm->getData();
            if (!empty($filtros['nome'])) {
                $deputados = array_filter($deputados, function($deputado) use ($filtros) {
                    return preg_match('/'.preg_quote($filtros['nome']).'/i', $deputado->nome);
                });
            }
            if (!empty($filtros['partido'])) {
                $deputados = array_filter($deputados, function($deputado) use ($filtros) {
                    return preg_match('/'.preg_quote($filtros['partido']).'/i', $deputado->partido);
                });
            }
            if (!empty($filtros['uf'])) {
                $deputados = array_filter($deputados, function($deputado) use ($filtros) {
                    return preg_match('/'.preg_quote($filtros['uf']).'/i', $deputado->uf);
                });
            }
        }
        */
       
        return $this->render('proposicao/lista.html.twig', [
            'filtroForm' => $filtroForm->createView(),
            'proposicoes' => $proposicoes,
            'proposicoesAno' => $proposicoesAno,
            'total' => count($proposicoes),
            'totalAno' => count($proposicoesAno),
            'ano' => date('Y'),
        ]);
    }
}
