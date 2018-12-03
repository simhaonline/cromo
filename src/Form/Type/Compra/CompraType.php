<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComCompra;
use App\Entity\Compra\ComCompraTipo;
use App\Entity\Compra\ComProveedor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('proveedorRel', EntityType::class, [
//                'required' => true,
//                'class' => ComProveedor::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('p')
//                        ->orderBy('p.nombreCorto', 'ASC');
//                },
//                'choice_label' => 'nombreCorto',
//                'label' => 'Proveedor:'
//            ])
            ->add('compraTipoRel', EntityType::class, [
                'class' => ComCompraTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Comprobante:'
            ])
            ->add('fechaFactura', DateType::class, ['label' => 'Fecha Factura:'])
            ->add('fechaVencimiento', DateType::class, ['label' => 'Fecha Vencimiento:'])
            ->add('numeroFactura', TextType::class, ['label' => 'Numero Factura:', 'required' => true])
            ->add('numeroOrdenCompra', TextType::class, ['label' => 'Numero Orden Compra:'])
            ->add('soporte', TextType::class, ['label' => 'Soporte', 'required' => false])
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComCompra::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCompraPk",                    "tipo":"pk",    "ayuda":"Codigo de compra",                       "titulo":"ID"},
            {"campo":"numeroCompra",                      "tipo":"entero","ayuda":"",                                       "titulo":"NUMERO"},
            {"campo":"proveedorRel.nombreCorto",          "tipo":"entero","ayuda":"Nombre del proveedor",                   "titulo":"NOMBRE","relacion":""},
            {"campo":"proveedorRel.numeroIdentificacion", "tipo":"entero","ayuda":"Numero de identificacion del proveedor", "titulo":"IDENTIFICACION","relacion":""},
            {"campo":"compraTipoRel.nombre",              "tipo":"texto", "ayuda":"",                                       "titulo":"TIPO", "relacion":""},
            {"campo":"fechaFactura",                      "tipo":"fecha", "ayuda":"Fecha de registro",                      "titulo":"FECHA"},
            {"campo":"estadoAutorizado",                  "tipo":"bool",  "ayuda":"",                                       "titulo":"AUT"},
            {"campo":"estadoAprobado",                    "tipo":"bool",  "ayuda":"",                                       "titulo":"APR"},
            {"campo":"estadoAnulado",                     "tipo":"bool",  "ayuda":"",                                       "titulo":"ANU"}                                
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoCompraPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoProveedorFk",  "tipo":"TextType",    "propiedades":{"label":"Codigo proveedor"}},
            {"child":"numeroCompra",       "tipo":"TextType",    "propiedades":{"label":"Numero compra"}},
            {"child":"codigoCompraTipoFk", "tipo":"TextType",    "propiedades":{"label":"Codigo compra tipo"}},
            {"child":"fechaFacturaDesde",  "tipo":"DateType",    "propiedades":{"label":"Fecha factura desde"}},
            {"child":"fechaFacturaHasta",  "tipo":"DateType",    "propiedades":{"label":"Fecha factura hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }
}
