<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuBanco;
use App\Entity\RecursoHumano\RhuConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BancoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoBancoPk', TextType::class, ['required' => true, 'label' => 'Codigo banco pk:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('codigoGeneral',TextType::class,['required' => true,'label' => 'Codigo general:'])
            ->add('codigoGeneralBancolombia',TextType::class,['required' => true,'label' => 'Codigo general bancolombia:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',TextType::class,['required' => true,'label' => 'Telefono:'])
            ->add('numeroDigitos',NumberType::class,['required' => true,'label' => 'Numero de digitos:'])
            ->add('guardar',SubmitType::class,['label' => 'guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuBanco::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoBancoPk","tipo":"pk"    ,"ayuda":"Codigo del banco" ,"titulo":"ID"},
            {"campo":"nombre", "tipo":"texto" ,"ayuda":"Nombre del banco" ,"titulo":"NOMBRE"},
            {"campo":"codigoGeneral", "tipo":"texto" ,"ayuda":"Codigo general del banco" ,"titulo":"CODIGO_GENERAL"},
            {"campo":"codigoGeneralBancolombia", "tipo":"texto" ,"ayuda":"Codigo general de bancolombia" ,"titulo":"CODIGO GENERAL BANCOLOMBIA"},                
            {"campo":"direccion",          "tipo":"texto" ,"ayuda":"Direccion de la entidad","titulo":"DIRECCION"},
            {"campo":"telefono",          "tipo":"texto" ,"ayuda":"Telefono de la entidad","titulo":"TELEFONO"},
            {"campo":"numeroDigitos",          "tipo":"texto" ,"ayuda":"Numero de digitos de la cuenta","titulo":"NUMERO DIGITOS"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoAdicionalPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoConceptoFk",  "tipo":"EntityType", "propiedades":{"class":"RhuConcepto","choice_label":"nombre","label":"Concepto"}},
            {"child":"codigoEmpleadoFk",  "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",        "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",        "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoInactivo",    "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"permanente",        "tipo":"ChoiceType", "propiedades":{"label":"permanente",     "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
