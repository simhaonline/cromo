<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoPagoTipoPk', TextType::class, ['required' => true, 'label' => 'Codigo pago tipo pk:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('cuentaPagarTipoRel', EntityType::class, [
                'class' => TesCuentaPagarTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cp')
                        ->orderBy('cp.nombre', 'ASC');
                },
                'label' => 'Cuenta pagar tipo:',
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('generaTesoreria', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuPagoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPagoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"cuentaPagarTipoRel.nombre",                    "tipo":"texto",     "ayuda":"Tipo de cuenta por pagar",                               "titulo":"CUENTA PAGAR TIPO",                "relacion":""},
            {"campo":"generaTesoreria",               "tipo":"bool",      "ayuda":"Si el tipo de pago va a generar cuenta por pagar en compras",          "titulo":"GENERA TESORERIA"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoPagoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"}
        ]';
        return $campos;
    }
}
