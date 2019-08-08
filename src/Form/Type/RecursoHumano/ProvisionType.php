<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuProvision;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProvisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuProvision::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoPensionPk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
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
            {"campo":"codigoPensionPk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},
            {"campo":"porcentajeEmpleado",  "tipo":"texto"  ,"ayuda":"Porcentaje empleado",  "titulo":"P_E"},
            {"campo":"porcentajeEmpleador", "tipo":"texto"  ,"ayuda":"Porcentaje empleador", "titulo":"P_ER"},
            {"campo":"conceptoRel.nombre",  "tipo":"texto"  ,"ayuda":"Orden",                "titulo":"CONCEPTO", "relacion":""},
            {"campo":"orden",               "tipo":"texto"  ,"ayuda":"Abreviatura",          "titulo":"ORDEN"}                                  
        ]';
    }
}
