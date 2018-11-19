<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCreditoTipoPk',TextType::class,['required' => true,'label' => 'Codigo credito tipo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('cupoMaximo',IntegerType::class,['required' => true,'label' => 'Cupo maximo:'])
            ->add('conceptoRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'required' => true,
                'choice_label' => 'nombre',
                'label' => 'Concepto:'
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCreditoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCreditoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"cupoMaximo",          "tipo":"moneda","ayuda":"Cupo maximo",          "titulo":"CUPO"},
            {"campo":"codigoConceptoFk",    "tipo":"texto", "ayuda":"Codigo concepto ",    "titulo":"CONCEPTO"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoCreditoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"cupoMaximo",          "tipo":"moneda","ayuda":"Cupo maximo",          "titulo":"CUPO"},
            {"campo":"codigoConceptoFk",    "tipo":"texto", "ayuda":"Codigo concepto ",    "titulo":"CONCEPTO"}
        ]';
        return $campos;
    }
}
