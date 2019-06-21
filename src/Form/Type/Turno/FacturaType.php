<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurPedido;
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

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plazoPago', NumberType::class, ['label' => 'Plazo pago', 'required' => false])
            ->add('soporte', TextType::class, ['label' => 'Soporte', 'required' => false])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurFactura::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoFacturaPk",               "tipo":"pk",    "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"numero",                         "tipo":"texto", "ayuda":"Consecutivo de aprobación",            "titulo":"NUMERO"},
            {"campo":"fecha",                          "tipo":"fecha", "ayuda":"Fecha",                                "titulo":"FECHA"},
            {"campo":"clienteRel.nombreCorto",          "tipo":"entero","ayuda":"Nombre del cliente",                   "titulo":"NOMBRE","relacion":""},
            {"campo":"vrSubtotal",                         "tipo":"moneda", "ayuda":"Subtotal",            "titulo":"SUBTOTAL"},
            {"campo":"vrIva",                         "tipo":"moneda", "ayuda":"Iva",            "titulo":"IVA"},
            {"campo":"vrNeto",                         "tipo":"moneda", "ayuda":"Neto",            "titulo":"NETO"},
            {"campo":"vrTotal",                         "tipo":"moneda", "ayuda":"Total",            "titulo":"TOTAL"},
            {"campo":"usuario",                        "tipo":"texto", "ayuda":"Usuario",                              "titulo":"USU"},            
            {"campo":"estadoAutorizado",               "tipo":"bool",  "ayuda":"Autorizado",                           "titulo":"AUT"},
            {"campo":"estadoAprobado",                 "tipo":"bool",  "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                  "tipo":"bool",  "ayuda":"Anulado",                              "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoFacturaPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}}
        ]';

        return $campos;
    }
}
