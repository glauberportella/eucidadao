<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('dtinicio', DateType::class, [
              'label' => 'Data Início',
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy',
              'required' => true,
              'attr' => ['class' => 'date'],
            ])
            ->add('dtfim', DateType::class, [
              'label' => 'Data Fim',
              'widget' => 'single_text',
              'format' => 'dd/MM/yyyy',
              'required' => true,
              'attr' => ['class' => 'date'],
            ])
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
