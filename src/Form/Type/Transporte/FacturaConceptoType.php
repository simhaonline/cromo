<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteFacturaConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('codigoFacturaConceptoPk',TextType::class,['required' => true,'label' => 'Codigo factura concepto:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteFacturaConcepto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoFacturaConceptoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"{"campo":"codigoFacturaConceptoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}
        ]';
    }
}
