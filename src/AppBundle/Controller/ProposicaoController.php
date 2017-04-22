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
        $periodo = $service->getPeriodoUtilProximaSemana(new \DateTime());
        // aplica filtros
        $filtroForm->handleRequest($request);
        if ($filtroForm->isSubmitted() && $filtroForm->isValid()) {
            $filtros = $filtroForm->getData();
            $periodo = [
              'inicio' => $filtros['dtinicio'],
              'fim' => $filtros['dtfim'],
            ];
        }

        $proposicoes = $service->listarProposicoesTramitadasNoPeriodo($periodo['inicio']->format('d/m/Y'), $periodo['fim']->format('d/m/Y'));

        return $this->render('proposicao/lista.html.twig', [
            'filtroForm' => $filtroForm->createView(),
            'hoje' => new \DateTime(),
            'periodo' => $periodo,
            'proposicoes' => $proposicoes,
            'total' => count($proposicoes),
        ]);
    }
}
