<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltroProposicaoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $proposicaoService = $options['proposicao_service'];
        $siglas = $proposicaoService->obterSiglasTipoProposicao();

        $builder
            ->add('sigla', ChoiceType::class, [
                'required' => true,
                'choices' => $siglas,
                'choice_label' => function($sigla) {
                    return sprintf('%s - %s', $sigla->tipoSigla, $sigla->descricao);
                },
                'choice_value' => function($sigla) {
                    if (!$sigla) {
                        return '';
                    }
                    return $sigla->tipoSigla;
                },
                'placeholder' => '',
            ])
            ->add('ano', null, ['required' => true])
            ->add('filtrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn-primary']])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('proposicao_service');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'filtro_proposicao';
    }
}
