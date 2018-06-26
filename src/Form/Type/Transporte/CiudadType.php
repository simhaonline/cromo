<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CiudadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rutaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteRuta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ruta:'
            ])
            ->add('departamentoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteDepartamento',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Departamento:'
            ])
            ->add('codigoCiudadPk',TextType::class,['required' => true,'label' => 'Codigo ciudad:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('codigoDivision',TextType::class,['required' => true,'label' => 'Codigo division:'])
            ->add('nombreDivision',TextType::class,['required' => true,'label' => 'Nombre division:'])
            ->add('codigoZona',TextType::class,['required' => true,'label' => 'Codigo zona:'])
            ->add('nombreZona',TextType::class,['required' => true,'label' => 'Nombre zona:'])
            ->add('codigoMunicipio',TextType::class,['required' => true,'label' => 'Codigo municipio:'])
            ->add('nombreMunicipio',TextType::class,['required' => true,'label' => 'Nombre municipio:'])
            ->add('ordenRuta',TextType::class,['required' => true,'label' => 'Orden ruta:'])
            ->add('codigoInterface',NumberType::class,['required' => true,'label' => 'Codigo interfaz:'])
            ->add('reexpedicion',TextType::class,['required' => true,'label' => 'Reexpedicion:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCiudad::class,
        ]);
    }
}
