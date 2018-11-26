<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuReclamo;
use App\Entity\RecursoHumano\RhuReclamoConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['label' => 'Empleado', 'required' => true])
            ->add('descripcion', TextareaType::class, ['attr' => ['rows' => '6']])
            ->add('responsable', TextType::class, ['label' => 'Responsable', 'required' => true])
            ->add('reclamoConceptoRel', EntityType::class, [
                'class' => RhuReclamoConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('comentario', TextareaType::class, ['attr' => ['rows' => '6'],'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
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
            {"campo":"empleadoRel.numeroIdentificacion",       "tipo":"texto"  ,"ayuda":"Identificacion del empleado" ,"titulo":"IDENTIFICACION", "relacion":""},
            {"campo":"empleadoRel.nombreCorto",       "tipo":"texto"  ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO", "relacion":"" },
            {"campo":"fecha",                  "tipo":"fecha"  ,"ayuda":"Fecha"               ,"titulo":"FECHA"},                     
            {"campo":"fechaCierre",            "tipo":"fecha"  ,"ayuda":"Fecha de cierre"     ,"titulo":"F.CIERRE"},                     
            {"campo":"responsable",            "tipo":"texto"  ,"ayuda":"Responable"          ,"titulo":"RESPONSABLE"},                     
            {"campo":"estadoAutorizado",       "tipo":"bool"   ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",         "tipo":"bool"   ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                                          
            {"campo":"estadoAnulado",          "tipo":"bool"   ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoReclamoPk",        "tipo":"pk"     ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoReclamoConceptoFk","tipo":"texto"  ,"ayuda":"Tipo de reclamo"     ,"titulo":"TIPO"},
            {"campo":"empleadoRel.numeroIdentificacion",       "tipo":"texto"  ,"ayuda":"Identificacion del empleado" ,"titulo":"IDENTIFICACION", "relacion":""},
            {"campo":"empleadoRel.nombreCorto",       "tipo":"texto"  ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO", "relacion":"" },
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
