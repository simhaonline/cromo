<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmbargoJuzgadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmbargoJuzgadoPk',TextType::class,['required' => true,'label' => 'Codigo juzgado:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('oficina',TextType::class,['required' => true,'label' => 'Oficina:'])
            ->add('cuenta',TextType::class,['required' => false, 'Cuenta:'])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmbargoJuzgado::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoEmbargoJuzgadoPk", "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",                 "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"oficina",                "tipo":"texto"  ,"ayuda":"Oficina",              "titulo":"OFICINA"},                                  
            {"campo":"cuenta",                 "tipo":"texto"  ,"ayuda":"Cuenta",               "titulo":"CUENTA"}                                  
        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        return '[
            {"campo":"codigoEmbargoJuzgadoPk", "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",                 "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"oficina",                "tipo":"texto"  ,"ayuda":"Oficina",              "titulo":"OFICINA"},                                  
            {"campo":"cuenta",                 "tipo":"texto"  ,"ayuda":"Cuenta",               "titulo":"CUENTA"}                                  
        ]';
    }
}
