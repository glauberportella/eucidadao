<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/como-funciona", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('default/about.html.twig', []);
    }

    /**
     * @Route("/deputados", name="deputados")
     */
    public function deputadosAction(Request $request)
    {
        $service = $this->container->get('camara_deputado_service');
        $deputados = $service->obterDeputados();
        return $this->render('default/deputados.html.twig', [
            'deputados' => $deputados,
        ]);
    }
}
