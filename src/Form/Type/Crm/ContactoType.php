<?php

namespace App\Form\Type\Crm;

use App\Entity\Crm\CrmContacto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('saludo', TextType::class, ['label' => 'saludo:', 'required' => true])
            ->add('cargo', TextType::class, ['label' => 'Cargo:', 'required' => true])
            ->add('especialidad', TextType::class, ['label' => 'Especialidad:', 'required' => true])
            ->add('horarioVisita', TextType::class, ['label' => 'Horario visita:', 'required' => true])
            ->add('secretaria', TextType::class, ['label' => 'Secretaria:', 'required' => true])
            ->add('correo', EmailType::class, ['label' => 'Correo:', 'required' => true])
            ->add('ciudadRel', EntityType::class, [
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Ciudad:',
            ])
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
            {"campo":"telefono",               "tipo":"texto"  ,"ayuda":"Telefono del contacto",   "titulo":"TELEFONO"},                                                                                      
            {"campo":"saludo",               "tipo":"texto"  ,"ayuda":"Saludo del contacto",   "titulo":"SALUDO"},                                                                                      
            {"campo":"cargo",               "tipo":"texto"  ,"ayuda":"cargo del contacto",   "titulo":"CARGO"},                                                                                      
            {"campo":"especialidad",               "tipo":"texto"  ,"ayuda":"Especialidad del contacto",   "titulo":"ESPECIALIDAD"},                                                                                      
            {"campo":"horarioVisita",               "tipo":"texto"  ,"ayuda":"Horario Visita del contacto",   "titulo":"HORARIO VISITA"},                                                                                      
            {"campo":"secretaria",               "tipo":"texto"  ,"ayuda":"secretaria del contacto",   "titulo":"SECRETARIA"}                                                                                      
        ]';
        return $campos;
    }
}
