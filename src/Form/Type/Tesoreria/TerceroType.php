<?php

namespace App\Form\Type\Tesoreria;


use App\Entity\Compra\ComProveedor;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenIdentificacion;
use App\Entity\Tesoreria\TesTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerceroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion', TextType::class, ['required' => true , 'label' => 'Identificacion:'])
            ->add('nombre1', TextType::class, ['required' => false, 'label' => 'Primer nombre:'])
            ->add('nombre2', TextType::class, ['required' => false, 'label' => 'Segundo nombre:'])
            ->add('apellido1', TextType::class, ['required' => false, 'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class, ['required' => false, 'label' => 'Segundo apellido:'])
            ->add('nombreCorto', TextType::class, ['required' => true, 'label' => 'Nombre corto:'])
            ->add('direccion', TextType::class, ['required' => true, 'label' => 'Direccion:'])
            ->add('telefono', TextType::class, ['required' => false, 'label' => 'Telefono:'])
            ->add('celular', TextType::class, ['required' => false, 'label' => 'Celular:'])
            ->add('fax', TextType::class, ['required' => false, 'label' => 'Fax:'])
            ->add('email', TextType::class, ['required' => false, 'label' => 'Email:'])
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
            'data_class' => TesTercero::class,
        ]);
    }

}
