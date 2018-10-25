<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('fechaInicio', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaFinalizacion', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaCredito', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('numeroCuotas', IntegerType::class, ['required' => true])
            ->add('comentarios', TextareaType::class, ['required' => false])
            ->add('seguro',NumberType::class,['required' => false])
            ->add('validarCuotas',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaPrima',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaCesantia',CheckboxType::class,['required' => false])
            ->add('creditoPagoRel',EntityType::class,[
                'class' => RhuCreditoPago::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('creditoTipoRel',EntityType::class,[
                'class' => RhuCreditoTipo::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCredito::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCreditoPk",                 "tipo":"pk"     ,"ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"creditoTipoRel.nombre",           "tipo":"rel"    ,"ayuda":"Tipo de credito",     "titulo":"TIPO"},
            {"campo":"codigoEmpleadoFk",                "tipo":"texto"  ,"ayuda":"Codigo del empleado", "titulo":"EMPLEADO"},
            {"campo":"empleadoRel.nombreCorto",         "tipo":"rel"    ,"ayuda":"Codigo del empleado", "titulo":"EMPLEADO"},
            {"campo":"empleadoRel.numeroIdentificacion","tipo":"rel"    ,"ayuda":"Codigo del empleado", "titulo":"EMPLEADO"},
            {"campo":"grupoRel.nombre",                 "tipo":"rel"    ,"ayuda":"Nombre del grupo del empleado","titulo":"GRUPO"},
            {"campo":"fecha",                           "tipo":"fecha"  ,"ayuda":"Fecha",               "titulo":"FECHA"},                     
            {"campo":"vrCuota",                         "tipo":"moneda" ,"ayuda":"Valor de la cuota",   "titulo":"V.CUOTA"},                     
            {"campo":"numeroCuotas",                    "tipo":"texto"  ,"ayuda":"Cantidad de cuotas",  "titulo":"CUOTAS"},                     
            {"campo":"estadoPagado",                    "tipo":"bool"   ,"ayuda":"Estado pagado",       "titulo":"PAG"},                     
            {"campo":"estadoSuspendido",                "tipo":"bool"   ,"ayuda":"Estado suspendido",   "titulo":"SUS"}                                          
        ]';
        return $campos;
    }
}
