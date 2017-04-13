<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltroDeputadoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome', null, ['required' => false])
            ->add('partido', null, ['required' => false])
            ->add('uf', ChoiceType::class, [
                'required' => false,
                'choices' => self::estados(),
                'choice_value' => function($value) {
                    return $value;
                },
                'placeholder' => ''
            ])
            ->add('filtrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn-primary']])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cidadao'
        ));
    }
    */

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'filtro_deputado';
    }

    static public function estados()
    {
        return [
            'Acre' => 'AC',
            'Alagoas' => 'AL',
            'Amazonas' => 'AM',
            'Amapá' => 'AP',
            'Bahia' => 'BH',
            'Ceará' => 'CE',
            'Distrito Federal' => 'DF',
            'Espírito Santo' => 'ES',
            'Goiás' => 'GO',
            'Maranhão' => 'MA',
            'Minas Gerais' => 'MG',
            'Mato Grosso do Sul' => 'MS',
            'Mato Grosso' => 'MT',
            'Pará' => 'PA',
            'Paraíba' => 'PB',
            'Pernambuco' => 'PE',
            'Piauí' => 'PI',
            'Paraná' => 'PR',
            'Rio de Janeiro' => 'RJ',
            'Rio Grande do Norte' => 'RN',
            'Rondônia' => 'RO',
            'Roraima' => 'RR',
            'Rio Grande do Sul' => 'RS',
            'Santa Catarina' => 'SC',
            'Sergipe' => 'SE',
            'São Paulo' => 'SP',
            'Tocantins' => 'TO',        
        ];
    }
}
