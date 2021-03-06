<?php

namespace App\Form\Type\General;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenTipoPersona;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion:'
            ])
            ->add('tipoPersonaRel',EntityType::class,[
                'required' => true,
                'class' => GenTipoPersona::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tp')
                        ->orderBy('tp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo persona:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('nit', TextType::class, ['required' => true])
            ->add('digitoVerificacion', NumberType::class, ['required' => true])
            ->add('contabilidadAutomatica', CheckboxType::class, ['required' => false,'label' => ' '])
            ->add('nombre', TextType::class, ['required' => true])
            ->add('rutaTemporal', TextType::class, ['required' => true])
            ->add('telefono', TextType::class, ['required' => true])
            ->add('direccion', TextType::class, ['required' => true])
            ->add('codigoClienteMesaAyuda', IntegerType::class, ['required' => true])
            ->add('webServiceGalioUrl', TextType::class, ['required' => false])
            ->add('webServiceCesioUrl', TextType::class, ['required' => false])
            ->add('dominio', TextType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Actualizar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenConfiguracion::class,
        ]);
    }
}
