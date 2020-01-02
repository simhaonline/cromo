<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteRutaRecogida;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RutaRecogidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoRutaRecogidaPk',TextType::class,['required' => true,'label' => 'Codigo ruta recogida:'])
            ->add('operacionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteOperacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('op')
                        ->orderBy('op.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Operacion:'
            ])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteRutaRecogida::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoRutaRecogidaPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"codigoOperacionFk",            "tipo":"texto",     "ayuda":"Codigo operacion",        "titulo":"CODIGO OPERACION"},	
            {"campo":"nombre",                       "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"campo":"codigoRutaRecogidaPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"codigoOperacionFk",            "tipo":"texto",     "ayuda":"Codigo operacion",        "titulo":"CODIGO OPERACION"},	
            {"campo":"nombre",                       "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}	
        ]';
    }
}
