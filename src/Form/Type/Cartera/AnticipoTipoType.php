<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAnticipoTipoPk', TextType::class, ['label'=> 'Codigo anticipo tipo pk', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('cuentaCobrarTipoRel',EntityType::class,[
                'class' => 'App\Entity\Cartera\CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cct')
                        ->orderBy('cct.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta cobrar tipo:',
                'required' => true
            ])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('orden', IntegerType::class, ['label' => 'Orden', 'required' => true])
            ->add('codigoComprobanteFk', TextType::class, ['label' => 'Comprobante:','required' => false])
            ->add('prefijo', TextType::class, ['label' => 'Prefijo:','required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAnticipoTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                       "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"CONSECUTIVO"},
            {"campo":"codigoComprobanteFk",                     "tipo":"texto"  ,"ayuda":"Comprobante contable",     "titulo":"COMPROBANTE"},
            {"campo":"prefijo",                     "tipo":"texto"  ,"ayuda":"Prefijo para contabilidad",     "titulo":"PREFIJO"}          
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoAnticipoTipoPk",    "tipo":"pk"     ,"ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"CONSECUTIVO"}                                         
        ]';
        return $campos;
    }
}
