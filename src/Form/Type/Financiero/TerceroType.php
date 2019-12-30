<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenCiudad;
use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenIdentificacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerceroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre1', TextType::class, ['required' => false, 'label' => 'Primer nombre:'])
            ->add('nombre2', TextType::class, ['required' => false, 'label' => 'Segundo nombre:'])
            ->add('apellido1', TextType::class, ['required' => false, 'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class, ['required' => false, 'label' => 'Segundo apellido:'])
            ->add('nombreCorto', TextType::class, ['label' => 'Nombre completo:'])
            ->add('identificacionRel', EntityType::class, [
                'required' => true,
                'class' => GenIdentificacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo identificación:'
            ])
            ->add('numeroIdentificacion', TextType::class, ['label' => 'Identificación:'])
            ->add('digitoVerificacion', NumberType::class, ['required' => false, 'label' => 'Digito verificación:'])
            ->add('razonSocial', TextType::class, ['label' => 'Razón social:'])
            ->add('ciudadRel', EntityType::class, [
                'required' => true,
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('direccion', TextType::class, ['required' => false, 'label' => 'Dirección:'])
            ->add('telefono', TelType::class, ['required' => false, 'label' => 'Telefono:'])
            ->add('celular', TelType::class, ['required' => false, 'label' => 'Celular:'])
            ->add('fax', TextType::class, ['required' => false, 'label' => 'Fax:'])
            ->add('email', EmailType::class, ['required' => false, 'label' => 'Correo:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinTercero::class,
        ]);
    }

}
