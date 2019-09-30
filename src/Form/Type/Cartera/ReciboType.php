<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarRecibo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:',
                'required' => true
            ])
            ->add('reciboTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Cartera\CarReciboTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo recibo:',
                'required' => true
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Asesor:'
            ])
            ->add('soporte', TextType::class, array('required' => false))
            ->add('fechaPago', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('numeroReferencia', NumberType::class, array('required' => false))
            ->add('numeroReferenciaPrefijo', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarRecibo::class,
        ]);
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"estadoAprobado","tipo":"ASC"},
            {"campo":"fecha","tipo":"DESC"},
            {"campo":"codigoReciboPk","tipo":"DESC"}
        ]';
        return $campos;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_factura';
    }
}
