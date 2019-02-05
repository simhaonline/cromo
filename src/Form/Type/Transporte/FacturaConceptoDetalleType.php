<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteFacturaConceptoDetalle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaConceptoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaConceptoDetallePk',TextType::class,['required' => true,'label' => 'Codigo factura concepto:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('impuestoRetencionRel', EntityType::class, [
                'class' => 'App\Entity\General\GenImpuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where("i.codigoImpuestoTipoFk = 'R'")
                        ->orderBy('i.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Retencion:'
                , 'required' => true])
            ->add('impuestoIvaVentaRel', EntityType::class, [
                'class' => 'App\Entity\General\GenImpuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where("i.codigoImpuestoTipoFk = 'I'")
                        ->orderBy('i.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Iva:'
                , 'required' => true])
            ->add('codigoCuentaFk',TextType::class,['required' => false,'label' => 'Codigo cuenta:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteFacturaConceptoDetalle::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoFacturaConceptoDetallePk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"codigoImpuestoRetencionFk",                      "tipo":"texto",     "ayuda":"RETENCION",     "titulo":"RET"},
            {"campo":"codigoImpuestoIvaVentaFk",                      "tipo":"texto",     "ayuda":"Iva",     "titulo":"IVA"},
            {"campo":"codigoCuentaFk",                      "tipo":"texto",     "ayuda":"Cuenta",     "titulo":"CUENTA"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"{"campo":"codigoFacturaConceptoDetallePk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}
        ]';
    }
}
