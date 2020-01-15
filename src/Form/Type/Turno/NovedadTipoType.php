<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurNovedadTipo;
use App\Entity\Turno\TurTurno;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovedadTipoType extends   AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('turnoRel', EntityType::class, array(
                'class' => TurTurno::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo:',
                'required' => true))
            ->add('nombre', TextType::class, array('required' => true, 'label' => 'Nombre:'))
            ->add('estadoLicencia', CheckboxType::class, array('required' => false))
            ->add('estadoIncapacidad', CheckboxType::class, array('required' => false))
            ->add('estadoIngreso', CheckboxType::class, array('required' => false))
            ->add('estadoRetiro', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurNovedadTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoNovedadTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"estadoLicencia",          "tipo":"bool",  "ayuda":"Estado licencia",           "titulo":"LIC"},
            {"campo":"estadoIncapacidad",          "tipo":"bool",  "ayuda":"Estado incapcidad",           "titulo":"INC"},
            {"campo":"estadoIngreso",          "tipo":"bool",  "ayuda":"Estado ingreso",           "titulo":"ING"},
            {"campo":"estadoRetiro",          "tipo":"bool",  "ayuda":"Estado retiro",           "titulo":"RET"}
        ]';
        return $campos;
    }


    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoNovedadTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"estadoLicencia",          "tipo":"bool",  "ayuda":"Operativo",           "titulo":"estadoLicencia"},
            {"campo":"estadoIncapacidad",          "tipo":"bool",  "ayuda":"Operativo",           "titulo":"estadoIncapacidad"},
            {"campo":"estadoIngreso",          "tipo":"bool",  "ayuda":"Operativo",           "titulo":"estadoIngreso"},
            {"campo":"estadoRetiro",          "tipo":"bool",  "ayuda":"Operativo",           "titulo":"estadoRetiro"}
        ]';
        return $campos;
    }
}