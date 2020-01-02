<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteOperacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OperacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('operacionCargoRel',EntityType::class,[
                'required' => true,
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Operacion:'
            ])
            ->add('codigoOperacionPk',TextType::class,['required' => true,'label' => 'Codigo operacion:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('codigoCuentaIngresoFleteFk',TextType::class,['required' => false,'label' => 'Cuenta flete (Ing):'])
            ->add('codigoCuentaIngresoManejoFk',TextType::class,['required' => false,'label' => 'Cuenta manejo (Ing):'])
            ->add('codigoCentroCostoFk',TextType::class,['required' => false,'label' => 'Centro costo:'])
            ->add('codigoCuentaDespachoFleteFk',TextType::class,['required' => false,'label' => 'Cuenta manejo (Ing):'])
            ->add('codigoCuentaDespachoRetencionFuenteFk',TextType::class,['required' => false,'label' => 'Cuenta despacho retencion ruente'])
            ->add('codigoCuentaDespachoIndustriaComercioFk',TextType::class,['required' => false,'label' => 'Cuenta despacho industria comercio'])
            ->add('codigoCuentaDespachoSeguridadFk',TextType::class,['required' => false,'label' => 'Cuenta despacho deguridad'])
            ->add('codigoCuentaDespachoEstampillaFk',TextType::class,['required' => false,'label' => 'Cuenta despacho estampilla'])
            ->add('codigoCuentaDespachoPapeleriaFk',TextType::class,['required' => false,'label' => 'Cuenta despacho papeleria'])
            ->add('codigoCuentaDespachoCargueFk',TextType::class,['required' => false,'label' => 'Cuenta despacho cargue'])
            ->add('codigoCuentaDespachoAnticipoFk',TextType::class,['required' => false,'label' => 'Cuenta despacho anticipo'])
            ->add('codigoCuentaDespachoPagarFk',TextType::class,['required' => false,'label' => 'Cuenta despacho pagar'])
            ->add('retencionIndustriaComercio', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteOperacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoOperacionPk",           "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"codigoCiudadFk",              "tipo":"texto",     "ayuda":"Codigo ciudad",           "titulo":"CODIGO CIUDAD"},	
            {"campo":"codigoCentroCostoFk",         "tipo":"texto",     "ayuda":"Codigo centro costo",     "titulo":"CODIGO CENTRO COSTO"},	
            {"campo":"codigoCuentaIngresoFleteFk",              "tipo":"texto",     "ayuda":"Codigo cuenta ingreso flete",           "titulo":"CTA FLETE"},	
            {"campo":"codigoCuentaIngresoManejoFk",              "tipo":"texto",     "ayuda":"Codigo cuenta ingreso manejo",           "titulo":"CTA MANEJO"}	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"campo":"codigoOperacionPk",           "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"codigoCiudadFk",              "tipo":"texto",     "ayuda":"Codigo ciudad",           "titulo":"CODIGO CIUDAD"},	
            {"campo":"codigoCentroCostoFk",         "tipo":"texto",     "ayuda":"Codigo centro costo",     "titulo":"CODIGO CENTRO COSTO"}	
        ]';
    }
}
