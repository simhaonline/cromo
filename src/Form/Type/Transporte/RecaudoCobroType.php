<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteRecaudoCobro;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecaudoCobroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('soporte',TextType::class,['required' => true,'label' => 'Soporte:'])
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteRecaudoCobro::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoRecaudoCobroPk",                    "tipo":"pk",        "ayuda":"Codigo de recaudo cobro",                  "titulo":"ID"},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"vrTotal",                                 "tipo":"moneda",    "ayuda":"Total",                                    "titulo":"TOTAL"},
            {"campo":"usuario",                                 "tipo":"texto",     "ayuda":"Usuario",                                  "titulo":"USUARIO"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
        ]';

        return $campos;
    }
}
