<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GuiaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facturaTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteFacturaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Factura tipo:'
            ])
            ->add('codigoGuiaTipoPk',TextType::class,['required' => true,'label' => 'Codigo guia tipo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('mensajeFormato',TextType::class,['required' => false,'label' => 'Mensaje formato:'])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('orden',NumberType::class,['required' => true,'label' => 'Orden:'])
            ->add('validarCaracteres',IntegerType::class,['required' => false,'label' => 'Validar caracteres:'])
            ->add('factura', CheckboxType::class, array('required'  => false))
            ->add('cortesia', CheckboxType::class, array('required'  => false))
            ->add('exigeNumero', CheckboxType::class, array('required'  => false))
            ->add('validarFlete', CheckboxType::class, array('required'  => false))
            ->add('validarRango', CheckboxType::class, array('required'  => false))
            ->add('generaCobro', CheckboxType::class, array('required'  => false))
            ->add('generaCobroEntrega', CheckboxType::class, array('required'  => false))
            ->add('btnGuardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteGuiaTipo::class,
        ]);
    }

}

