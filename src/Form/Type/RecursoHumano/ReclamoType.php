<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuReclamo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk')
            ->add('codigoReclamoConceptoFk')
            ->add('fechaRegistro')
            ->add('fecha')
            ->add('fechaCierre')
            ->add('fechaSolucion')
            ->add('descripcion')
            ->add('comentarios')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('responsable')
            ->add('usuario')
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuReclamo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoReclamoPk",        "tipo":"pk"     ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoReclamoConceptoFk","tipo":"texto"  ,"ayuda":"Tipo de reclamo"     ,"titulo":"TIPO"},
            {"campo":"codigoEmpleadoFk",       "tipo":"texto"  ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO"},
            {"campo":"fecha",                  "tipo":"fecha"  ,"ayuda":"Fecha"               ,"titulo":"FECHA"},                     
            {"campo":"fechaCierre",            "tipo":"fecha"  ,"ayuda":"Fecha de cierre"     ,"titulo":"F.CIERRE"},                     
            {"campo":"responsable",            "tipo":"texto"  ,"ayuda":"Responable"          ,"titulo":"RESPONSABLE"},                     
            {"campo":"estadoAutorizado",       "tipo":"bool"   ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",         "tipo":"bool"   ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                                          
            {"campo":"estadoAnulado",          "tipo":"bool"   ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}                                          
        ]';
        return $campos;
    }
}
