<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre", TextType::class, ['required'=>true, 'label'=>'nombre'])
            ->add('ingreso', CheckboxType::class, array('required'  => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamenTipo::class,
        ]);
    }

//    public function getEstructuraPropiedadesLista()
//    {
//        $campos = '[
//            {"campo":"codigoExamenTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
//            {"campo":"nombre",        "tipo":"nombre",   "ayuda":"nombre",    "titulo":"NOMBRE"},
//            {"campo":"ingreso",    "tipo":"bool",  "ayuda":"Remunerada",    "titulo":"INGRESO"}
//        ]';
//        return $campos;
//    }

//    public function getEstructuraPropiedadesExportar()
//    {
//        $campos = '[
//            {"campo":"codigoExamenTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
//            {"campo":"nombre",        "tipo":"nombre",   "ayuda":"nombre",    "titulo":"NOMBRE"},
//            {"campo":"ingreso",    "tipo":"bool",  "ayuda":"Ingreso",    "titulo":"INGRESO"}
//        ]';
//        return $campos;
//    }


}
