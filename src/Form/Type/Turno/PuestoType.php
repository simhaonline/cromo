<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurProgramador;
use App\Entity\Turno\TurPuesto;
use Doctrine\ORM\EntityRepository;

use Proxies\__CG__\App\Entity\General\GenCiudad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PuestoType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('programadorRel',EntityType::class,[
                'class' => TurProgramador::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('TurPro')
                        ->orderBy('TurPro.codigoProgramadorPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('ciudadRel',EntityType::class,[
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('GenCi')
                        ->orderBy('GenCi.codigoCiudadPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('nombre', TextType::class)
            ->add('direccion', TextType::class)
            ->add('telefono', IntegerType::class)
            ->add('celular', IntegerType::class)
            ->add('comunicacion', TextType::class)
            ->add('comentario', TextareaType::class)
            ->add('codigoCentroCostoFk', TextType::class)
            ->add('ubicacionGps', TextType::class)
            ->add('estadoInactivo', CheckboxType::class)
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurPuesto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPuestoPk",  "tipo":"pk"      ,"ayuda":"Codigo del registro"                      ,"titulo":"ID"},
            {"campo":"nombre",          "tipo":"texto"   ,"ayuda":"Nombre del puesto"                       ,"titulo":"NOMBRE"}

        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoPuestoPk",      "tipo":"TextType",    "propiedades":{"label":"codigoPuestoPk"}},
            {"child":"nombre",      "tipo":"TextType",    "propiedades":{"label":"nombre"}}
        ]';
        return $campos;
    }
}