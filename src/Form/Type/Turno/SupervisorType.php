<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurSupervisor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupervisorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSupervisorPk', TextType::class, ['required' => true, 'label'=>'Codigo'])
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('numeroIdentificacion', TextType::class, ['required' => true, 'label'=>'Identificacion'])
            ->add('correo', EmailType::class, ['required' => true, 'label'=>'Correo'])
            ->add('celular', TextType::class, ['required' => true, 'label'=>'Celular'])
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurSupervisor::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoSupervisorPk",      "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},	
            {"campo":"nombre",                  "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"},
            {"campo":"numeroIdentificacion",    "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"IDENTIFICACIÓN"},
            {"campo":"correo",                  "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"CORREO"},
            {"campo":"celular",                 "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"CELULAR"}
        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoSupervisorPk",      "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},	
            {"campo":"nombre",                  "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"},
            {"campo":"numeroIdentificacion",    "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"IDENTIFICACIÓN"},
            {"campo":"correo",                  "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"CORREO"},
            {"campo":"celular",                 "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"CELULAR"}
        ]';
        return $campos;
    }
}