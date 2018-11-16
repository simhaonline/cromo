<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuSalud;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaludType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSaludPk', TextType::class, ['required' => true, 'label' => 'Codigo salud:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('porcentajeEmpleado', NumberType::class, ['required' => true, 'label' => 'Porcentaje empleado:'])
            ->add('porcentajeEmpleador', NumberType::class, ['required' => true, 'label' => 'Porcentaje empleador:'])
            ->add('orden', IntegerType::class, ['required' => true, 'label' => 'Orden:'])
            ->add('conceptoRel', EntityType::class, [
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class'=> 'form-control to-select-2']
            ])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSalud::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoSaludPk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},
            {"campo":"porcentajeEmpleado",  "tipo":"texto"  ,"ayuda":"Porcentaje empleado",  "titulo":"P_E"},
            {"campo":"porcentajeEmpleador", "tipo":"texto"  ,"ayuda":"Porcentaje empleador", "titulo":"P_ER"},
            {"campo":"conceptoRel.nombre",  "tipo":"texto"  ,"ayuda":"Orden",                "titulo":"CONCEPTO", "relacion":""},
            {"campo":"orden",               "tipo":"texto"  ,"ayuda":"Abreviatura",          "titulo":"ORDEN"}                                  
        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        return '[
            {"campo":"codigoSaludPk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},
            {"campo":"porcentajeEmpleado",  "tipo":"texto"  ,"ayuda":"Porcentaje empleado",  "titulo":"P_E"},
            {"campo":"porcentajeEmpleador", "tipo":"texto"  ,"ayuda":"Porcentaje empleador", "titulo":"P_ER"},
            {"campo":"conceptoRel.nombre",  "tipo":"texto"  ,"ayuda":"Orden",                "titulo":"CONCEPTO", "relacion":""},
            {"campo":"orden",               "tipo":"texto"  ,"ayuda":"Abreviatura",          "titulo":"ORDEN"}                                  
        ]';
    }
}
