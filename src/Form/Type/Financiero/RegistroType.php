<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel', EntityType::class, [
                'required' => true,
                'class' => FinCuenta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:'
            ])
            ->add('terceroRel', EntityType::class, [
                'required' => true,
                'class' => FinTercero::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Tercero:'
            ])
            ->add('comprobanteRel', EntityType::class, [
                'required' => true,
                'class' => FinComprobante::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Comprobante:'
            ])
            ->add('centroCostoRel', EntityType::class, [
                'required' => true,
                'class' => FinCentroCosto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Centro costo:'
            ])
            ->add('vrDebito', NumberType::class, ['label' => 'Debito:'])
            ->add('vrCredito', NumberType::class, ['label' => 'Credito:'])
            ->add('vrBase', NumberType::class, ['label' => 'Base:'])
            ->add('descripcion', TextareaType::class, ['label' => 'Descripción:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinRegistro::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoRegistroPk",      "tipo":"pk"    , "ayuda":"Código del registro",        "titulo":"ID"},
            {"campo":"numero",                "tipo":"texto" , "ayuda":"Numero",                     "titulo":"NUMERO"},
            {"campo":"terceroRel.nombreCorto","tipo":"texto" , "ayuda":"Nombre del tercero",        "titulo":"TERCERO", "relacion":""},
            {"campo":"codigoCuentaFk",        "tipo":"texto" , "ayuda":"Código de la cuenta",        "titulo":"CUENTA"},
            {"campo":"codigoComprobanteFk",   "tipo":"texto" , "ayuda":"Código del registro",        "titulo":"COMPROBANTE"},
            {"campo":"codigoCentroCostoFk",   "tipo":"texto" , "ayuda":"Código del centro de costo", "titulo":"CENTRO_COSTO"},
            {"campo":"fecha",                 "tipo":"fecha" , "ayuda":"Fecha",                      "titulo":"FECHA"},
            {"campo":"vrDebito",              "tipo":"moneda", "ayuda":"Débito",                     "titulo":"DEBITO"},
            {"campo":"vrCredito",             "tipo":"moneda", "ayuda":"Crédito",                    "titulo":"CREDITO"},
            {"campo":"vrBase",                "tipo":"moneda", "ayuda":"Base",                       "titulo":"BASE"},
            {"campo":"descripcion",           "tipo":"texto" , "ayuda":"Descripción contable",       "titulo":"DESCRIPCION"}
        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        return '[
            {"campo":"codigoRegistroPk",      "tipo":"pk"    , "ayuda":"Código del registro",        "titulo":"ID"},
            {"campo":"terceroRel.nombreCorto","tipo":"texto" , "ayuda":"Nombre del tercero",        "titulo":"TERCERO", "relacion":""},
            {"campo":"codigoCuentaFk",        "tipo":"texto" , "ayuda":"Código de la cuenta",        "titulo":"CUENTA"},
            {"campo":"codigoComprobanteFk",   "tipo":"texto" , "ayuda":"Código del registro",        "titulo":"COMPROBANTE"},
            {"campo":"codigoCentroCostoFk",   "tipo":"texto" , "ayuda":"Código del centro de costo", "titulo":"CENTRO_COSTO"},
            {"campo":"fecha",                 "tipo":"fecha" , "ayuda":"Fecha",                      "titulo":"FECHA"},
            {"campo":"numero",                "tipo":"texto" , "ayuda":"Numero",                     "titulo":"NUMERO"},
            {"campo":"vrDebito",              "tipo":"moneda", "ayuda":"Débito",                     "titulo":"DEBITO"},
            {"campo":"vrCredito",             "tipo":"moneda", "ayuda":"Crédito",                    "titulo":"CREDITO"},
            {"campo":"vrBase",                "tipo":"moneda", "ayuda":"Base",                       "titulo":"BASE"},
            {"campo":"descripcion",           "tipo":"texto" , "ayuda":"Descripción contable",       "titulo":"DESCRIPCION"}
        ]';
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoTerceroFk", "tipo":"TextType" , "propiedades":{"label":"Tercero"}},
            {"child":"numero", "tipo":"TextType", "propiedades":{"label":"Numero"}},
            {"child":"codigoComprobanteFk","tipo":"EntityType","propiedades":{"class":"FinComprobante","choice_label":"nombre","label":"Comprobante"}},
            {"child":"fechaDesde","tipo":"DateType","propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta","tipo":"DateType","propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoIntercambio","tipo":"ChoiceType","propiedades":{"label":"Intercambio","choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
