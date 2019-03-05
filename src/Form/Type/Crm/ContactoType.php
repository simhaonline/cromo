<?php

namespace App\Form\Type\Crm;

use App\Entity\Crm\CrmContacto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion', TextType::class, ['label' => 'Numero identificacion:','required' => true])
            ->add('nombreCorto', TextType::class, ['label' => 'Nombre:', 'required' => true])
            ->add('direccion', TextType::class, ['label' => 'Direccion:', 'required' => true])
            ->add('telefono', TextType::class, ['label' => 'Telefono:', 'required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CrmContacto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoContactoPk",       "tipo":"pk"     ,"ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"numeroIdentificacion",   "tipo":"texto"  ,"ayuda":"Numero identificacion",   "titulo":"IDENTIFICACION"},
            {"campo":"nombreCorto",            "tipo":"texto"  ,"ayuda":"Nombre del contacto",     "titulo":"NOMBRE"},
            {"campo":"direccion",              "tipo":"texto"  ,"ayuda":"Direccion del contacto",  "titulo":"DIRECCION"},
            {"campo":"telefono",               "tipo":"texto"  ,"ayuda":"Telefono del contacto",   "titulo":"TELEFONO"}                                                                                      
        ]';
        return $campos;
    }
}
