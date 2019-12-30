<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DespachoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDespachoTipoPk', TextType::class, ['required' => true, 'label' => 'Codigo despacho tipo pk:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('codigoDespachoClaseFk', ChoiceType::class, [
                'choices' => array(
                    'VIAJE' => 'V', 'REPARTO' => 'R',
                ),
                'required' => true,
                'label' => 'Clase:'
            ])
            ->add('cuentaPagarTipoRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\Tesoreria\TesCuentaPagarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta por pagar:'
            ])
            ->add('cuentaPagarTipoAnticipoRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\Tesoreria\TesCuentaPagarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta por pagar anticipo:'
            ])
            ->add('consecutivo', NumberType::class, ['required' => true, 'label' => 'Consecutivo:'])
            ->add('exigeNumero', CheckboxType::class, array('required' => false))
            ->add('generaMonitoreo', CheckboxType::class, array('required' => false))
            ->add('viaje', CheckboxType::class, array('required' => false))
            ->add('contabilizar', CheckboxType::class, array('required' => false))
            ->add('generaCuentaPagar', CheckboxType::class, array('required' => false))
            ->add('codigoComprobanteFk', TextType::class, ['required' => false, 'label' => 'Codigo comprobante:'])
            ->add('codigoCuentaFleteFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta flete:'])
            ->add('codigoCuentaRetencionFuenteFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta retencion fuente:'])
            ->add('codigoCuentaIndustriaComercioFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta industria comercio:'])
            ->add('codigoCuentaSeguridadFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta seguridad:'])
            ->add('codigoCuentaCargueFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta cargue:'])
            ->add('codigoCuentaEstampillaFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta estampilla:'])
            ->add('codigoCuentaPapeleriaFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta papeleria:'])
            ->add('codigoCuentaAnticipoFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta anticipo:'])
            ->add('codigoCuentaPagarFk', TextType::class, ['required' => false, 'label' => 'Codigo cuenta pagar:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDespachoTipo::class,
        ]);
    }

}
