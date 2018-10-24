<?php

namespace App\Form\Type\Compra;


use App\Entity\Compra\ComProveedor;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenIdentificacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion', TextType::class, ['required' => true])
            ->add('digitoVerificacion', TextType::class, ['required' => false])
            ->add('nombre1', TextType::class, ['required' => false])
            ->add('nombre2', TextType::class, ['required' => false])
            ->add('apellido1', TextType::class, ['required' => false])
            ->add('apellido2', TextType::class, ['required' => false])
            ->add('nombreCorto', TextType::class, ['required' => true])
            ->add('direccion', TextType::class, ['required' => true])
            ->add('telefono', TextType::class, ['required' => false])
            ->add('celular', TextType::class, ['required' => false])
            ->add('fax', TextType::class, ['required' => false])
            ->add('email', TextType::class, ['required' => false])
            ->add('plazoPago', IntegerType::class, ['required' => false])
            ->add('ciudadRel', EntityType::class, [
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoCiudadPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('identificacionRel', EntityType::class, [
                'class' => GenIdentificacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.codigoIdentificacionPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo identificacion:'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComProveedor::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoProveedorPk",      "tipo":"pk",        "ayuda":"Codigo de compra",   "titulo":"ID"},
            {"campo":"numeroIdentificacion",   "tipo":"entero",    "ayuda":"",                     "titulo":"Nit"},
            {"campo":"nombreCorto",            "tipo":"texto",     "ayuda":"",                     "titulo":"COMP"}
        ]';
        return $campos;
    }
}
