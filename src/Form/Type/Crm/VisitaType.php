<?php

namespace App\Form\Type\Crm;

use App\Entity\Crm\CrmCliente;
use App\Entity\Crm\CrmContacto;
use App\Entity\Crm\CrmVisitaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitaTipoRel', EntityType::class, array(
                'class' => CrmVisitaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.codigoVisitaTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('clienteRel', EntityType::class, array(
                'class' => CrmCliente::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoClientePk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombreCorto',
            ))
            ->add('contactoRel', EntityType::class, array(
                'class' => CrmContacto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoContactoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombreCorto',
            ))
            ->add('comentarios',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Crm\CrmVisita'
        ]);
    }

}
