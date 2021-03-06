<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

use AppBundle\Form\CidadaoType;
use AppBundle\Form\FiltroDeputadoType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $signupForm = $this->createForm(CidadaoType::class);

        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $cidadao = $signupForm->getData();
            $em->persist($cidadao);
            $em->flush();

            // success message
            $this->addFlash('success', 'Seu cadastro foi efetuado com sucesso! Agradecemos pelo apoio.');

            return $this->redirect($this->generateUrl(
                'homepage'
            ));
        }

        return $this->render('default/index.html.twig', [
            'signupForm' => $signupForm->createView(),
        ]);
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

        $filtroForm = $this->createForm(FiltroDeputadoType::class);

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

        return $this->render('default/deputados.html.twig', [
            'filtroForm' => $filtroForm->createView(),
            'deputados' => $deputados,
            'total' => count($deputados),
        ]);
    }

    /**
     * @Route("/privacidade", name="privacidade")
     */
    public function privacidadeAction(Request $request)
    {
        return $this->render('default/privacidade.html.twig', []);
    }
}
