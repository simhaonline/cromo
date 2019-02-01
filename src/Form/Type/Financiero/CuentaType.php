<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinCuenta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPk', TextType::class, ['label' => 'Codigo cuenta:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('exigeTercero', CheckboxType::class, ['label' => 'Exige tercero:', 'required' => false])
            ->add('exigeCentroCosto', CheckboxType::class, ['label' => 'Exige centro de costo', 'required' => false])
            ->add('exigeBase', CheckboxType::class, ['label' => 'Exige base', 'required' => false])
            ->add('permiteMovimiento', CheckboxType::class, ['label' => 'Permite movimientos', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinCuenta::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaPk",          "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre de la cuenta",       "titulo":"NOMBRE"},
            {"campo":"clase",                  "tipo":"texto"  ,"ayuda":"Clase",       "titulo":"CLASE"},
            {"campo":"grupo",                  "tipo":"texto"  ,"ayuda":"Grupo",       "titulo":"GRUPO"},
            {"campo":"cuenta",                  "tipo":"texto"  ,"ayuda":"Cuenta",       "titulo":"CUENTA"},
            {"campo":"subcuenta",                  "tipo":"texto"  ,"ayuda":"Subcuenta",       "titulo":"SUBCUENTA"}, 
            {"campo":"exigeTercero",            "tipo":"bool"  ,"ayuda":"La cuenta exige tercero",     "titulo":"E_T"},
            {"campo":"exigeCentroCosto",        "tipo":"bool"  ,"ayuda":"La cuenta exige centro de costos",     "titulo":"E_CC"},
            {"campo":"exigeBase",               "tipo":"bool"  ,"ayuda":"Exige base",     "titulo":"E_B"},
            {"campo":"permiteMovimiento",       "tipo":"bool"  ,"ayuda":"La cuenta permite movimientos",     "titulo":"P_M"}            
                                                                          
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"nombre",                     "tipo":"TextType",   "propiedades":{"label":"Nombre"},      "operador":"like"},
            {"child":"codigoCuentaPk",                     "tipo":"TextType",   "propiedades":{"label":"codigo"},      "operador":"like"}            
        ]';

        return $campos;
    }
    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoCuentaPk",          "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre de la cuenta",       "titulo":"NOMBRE"},
            {"campo":"codigoCuentaPadreFk",     "tipo":"texto"  ,"ayuda":"Cuenta padre",       "titulo":"CUENTA PADRE"},   
            {"campo":"exigeTercero",            "tipo":"bool"  ,"ayuda":"La cuenta exige tercero",     "titulo":"EXIGE TERCERO"},
            {"campo":"exigeCentroCosto",        "tipo":"bool"  ,"ayuda":"La cuenta exige centro de costos",     "titulo":"EXIGE CENTRO COSTO"},
            {"campo":"exigeBase",               "tipo":"bool"  ,"ayuda":"Exige base",     "titulo":"EXIGE BASE DE RETENCION"},
            {"campo":"permiteMovimiento",       "tipo":"bool"  ,"ayuda":"La cuenta permite movimientos",     "titulo":"PERMITE MOVIMIENTO"}                                    
        ]';
        return $campos;
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"codigoCuentaPk","tipo":"ASC"}
        ]';
        return $campos;
    }
}
