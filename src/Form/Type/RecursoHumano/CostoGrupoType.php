<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\RecursoHumano\RhuCostoGrupo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostoGrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCostoGrupoPk',TextType::class,['required' => true, 'label' => 'Codigo costo grupo:'])
            ->add('nombre',TextType::class,['required' => true, 'label' => 'Nombre:'])
            ->add('centroCostoRel',EntityType::class,[
                'class' => FinCentroCosto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Centro costo:'
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCostoGrupo::class,
        ]);
    }


}
