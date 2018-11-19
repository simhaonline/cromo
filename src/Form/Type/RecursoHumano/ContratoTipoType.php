<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuContratoClase;
use App\Entity\RecursoHumano\RhuContratoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoContratoTipoPk',TextType::class,['required' => true,'label' => 'Codigo contrato tipo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('contratoClaseRel',EntityType::class,[
                'class' => RhuContratoClase::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Contrato clase:'
            ])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuContratoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoContratoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",            "titulo":"ID"},
            {"campo":"nombre",               "tipo":"texto", "ayuda":"Nombre del registro",            "titulo":"NOMBRE"},
            {"campo":"codigoContratoClaseFk","tipo":"texto", "ayuda":"Codigo de la clase de contrato", "titulo":"CLASE"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoContratoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",            "titulo":"ID"},
            {"campo":"nombre",               "tipo":"texto", "ayuda":"Nombre del registro",            "titulo":"NOMBRE"},
            {"campo":"codigoContratoClaseFk","tipo":"texto", "ayuda":"Codigo de la clase de contrato", "titulo":"CLASE"}
        ]';
        return $campos;
    }
}
