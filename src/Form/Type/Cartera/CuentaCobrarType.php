<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarCuentaCobrar;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaCobrarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('cuentaCobrarTipoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Cartera\CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.saldoInicial = 1')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta por cobrar:'
            ])
            ->add('numeroDocumento', IntegerType::class, array('required' => false))
            ->add('soporte', TextType::class, array('required' => false))
            ->add('plazo', IntegerType::class, array('required' => true))
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('vrSaldoOriginal', IntegerType::class, array('required' => true))
            ->add('vrSubtotal', IntegerType::class, array('required' => true))
            ->add('vrTotal', IntegerType::class, array('required' => true))
            ->add('vrRetencionFuente', IntegerType::class, array('required' => true))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarCuentaCobrar::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaCobrarPk",           "tipo":"pk",       "ayuda":"Codigo del registro",     "titulo":"ID"},                        
            {"campo":"cuentaCobrarTipoRel.nombre",     "tipo":"texto",    "ayuda":"Tipo de registro",     "titulo":"TIPO","relacion":""},            
            {"campo":"numeroDocumento",                "tipo":"texto",    "ayuda":"Numero de documento",     "titulo":"NUMERO"},                        
            {"campo":"numeroReferencia",               "tipo":"texto",    "ayuda":"Numero de referencia",     "titulo":"REF"},            
            {"campo":"fecha",                          "tipo":"fecha",    "ayuda":"Fecha de registro",     "titulo":"FECHA"},            
            {"campo":"fechaVence",                     "tipo":"fecha",    "ayuda":"Fecha en que vence el registro",     "titulo":"VENCE"},            
            {"campo":"clienteRel.numeroIdentificacion","tipo":"texto",    "ayuda":"Numero de identificacion del cliente",     "titulo":"IDENTIFICACION","relacion":""},            
            {"campo":"clienteRel.nombreCorto",         "tipo":"texto",    "ayuda":"Nombre del cliente",     "titulo":"CLIENTE","relacion":""},                        
            {"campo":"plazo",                          "tipo":"texto",    "ayuda":"Plazo de pago del cliente",     "titulo":"PLAZO"},            
            {"campo":"vrSaldoOriginal",                        "tipo":"moneda",   "ayuda":"Valor del saldo original",    "titulo":"ORIGINAL"},
            {"campo":"vrAbono",                        "tipo":"moneda",   "ayuda":"Valor del abono",    "titulo":"ABONO"},            
            {"campo":"vrSaldo",                        "tipo":"moneda",   "ayuda":"Valor del saldo",    "titulo":"SALDO"},            
            {"campo":"vrSaldoOperado",                 "tipo":"moneda",   "ayuda":"Valor del saldo operado",    "titulo":"S_O"}                                    
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"numeroDocumento",         "tipo":"TextType",    "propiedades":{"label":"Numero documento"}},
            {"child":"numeroReferencia",        "tipo":"TextType",    "propiedades":{"label":"Numero referencia"}},
            {"child":"codigoCuentaCobrarPk",    "tipo":"TextType",    "propiedades":{"label":"Codigo cuenta cobrar"}},
            {"child":"codigoClienteFk",         "tipo":"TextType",    "propiedades":{"label":"Codigo cliente"}},
            {"child":"codigoCuentaCobrarTipoFk","tipo":"EntityType",  "propiedades":{"class":"CarCuentaCobrarTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"fechaDesde",              "tipo":"DateType",    "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",              "tipo":"DateType",    "propiedades":{"label":"Fecha Hasta"}}
        ]';
        return $campos;
    }
}

