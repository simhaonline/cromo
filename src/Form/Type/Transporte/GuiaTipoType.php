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
            ->add('factura', CheckboxType::class, array('required'  => false))
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('validarCaracteres',IntegerType::class,['required' => true,'label' => 'Validar caracteres:'])
            ->add('cortesia', CheckboxType::class, array('required'  => false))
            ->add('exigeNumero', CheckboxType::class, array('required'  => false))
            ->add('validarFlete', CheckboxType::class, array('required'  => false))
            ->add('validarRango', CheckboxType::class, array('required'  => false))
            ->add('generaCobro', CheckboxType::class, array('required'  => false))
            ->add('generaCobroEntrega', CheckboxType::class, array('required'  => false))
            ->add('orden',NumberType::class,['required' => true,'label' => 'Orden:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteGuiaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoGuiaTipoPk",            "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"},
            {"campo":"factura",                     "tipo":"bool",      "ayuda":"Factura",                  "titulo":"FACTURA"},
            {"campo":"consecutivo",                 "tipo":"texto",     "ayuda":"Consecutivo",              "titulo":"CONSECUTIVO"},
            {"campo":"codigoFacturaTipoFk",         "tipo":"texto",     "ayuda":"Codigo factura tipo",      "titulo":"CODIGO FACTURA TIPO"},
            {"campo":"exigeNumero",                 "tipo":"bool",      "ayuda":"Exige numero",             "titulo":"EXIGE NUMERO"},
            {"campo":"orden",                       "tipo":"numero",    "ayuda":"Orden",                    "titulo":"ORDEN"},
            {"campo":"validarFlete",                "tipo":"bool",      "ayuda":"Validar flete",            "titulo":"VALIDAR FLETE"},
            {"campo":"validarRango",                "tipo":"bool",      "ayuda":"Validar rango",            "titulo":"VALIDAR RANGO"},
            {"campo":"cortesia",                    "tipo":"bool",      "ayuda":"Cortesia",                 "titulo":"CORTESIA"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoGuiaTipoPk",            "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"},
            {"campo":"factura",                     "tipo":"bool",      "ayuda":"Factura",                  "titulo":"FACTURA"},
            {"campo":"consecutivo",                 "tipo":"texto",     "ayuda":"Consecutivo",              "titulo":"CONSECUTIVO"},
            {"campo":"codigoFacturaTipoFk",         "tipo":"texto",     "ayuda":"Codigo factura tipo",      "titulo":"CODIGO FACTURA TIPO"},
            {"campo":"exigeNumero",                 "tipo":"bool",      "ayuda":"Exige numero",             "titulo":"EXIGE NUMERO"},
            {"campo":"orden",                       "tipo":"numero",    "ayuda":"Orden",                    "titulo":"ORDEN"},
            {"campo":"validarFlete",                "tipo":"bool",      "ayuda":"Validar flete",            "titulo":"VALIDAR FLETE"},
            {"campo":"validarRango",                "tipo":"bool",      "ayuda":"Validar rango",            "titulo":"VALIDAR RANGO"},
            {"campo":"cortesia",                    "tipo":"bool",      "ayuda":"Cortesia",                 "titulo":"CORTESIA"}
        ]';
    }
}

