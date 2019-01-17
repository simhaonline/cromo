<?php

namespace App\Form\Type\General;

use App\Entity\General\GenBanco;
use App\Entity\General\GenCuenta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPk', TextType::class, ['required' => true])
            ->add('nombre', TextType::class, ['required' => true])
            ->add('cuenta', TextType::class, ['required' => true])
            ->add('tipo', TextType::class, ['required' => true])
            ->add('codigoCuentaContableFk', TextType::class, ['required' => true])
            ->add('bancoRel',EntityType::class,[
                'class' => GenBanco::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenCuenta::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaPk",         "tipo":"pk",    "ayuda":"Codigo del registro",    "titulo":"ID"},
            {"campo":"nombre",                 "tipo":"texto", "ayuda":"Nombre del registro",    "titulo":"NOMBRE"},            
            {"campo":"bancoRel.nombre",        "tipo":"texto", "ayuda":"Nombre del banco",       "titulo":"BANCO", "relacion":""},            
            {"campo":"cuenta",                 "tipo":"texto", "ayuda":"Cuenta",                 "titulo":"CUENTA"},            
            {"campo":"tipo",                   "tipo":"texto", "ayuda":"Tipo de cuenta",         "titulo":"TIPO"},            
            {"campo":"codigoCuentaContableFk", "tipo":"texto", "ayuda":"Codigo cuenta contable", "titulo":"CUENTA CONTABLE"}            
        ]';
        return $campos;
    }
}
