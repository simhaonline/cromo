<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Entity\Transporte\TteProducto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PrecioDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadOrigenRel',EntityType::class,[
                'required' => true,
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => function($er){
                    $ciudad = $er->getNombre();
                    return $ciudad.' - '.$er->getDepartamentoRel()->getNombre();
                },
                'label' => 'Ciudad origen:'
            ])
            ->add('ciudadDestinoRel',EntityType::class,[
                'required' => true,
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => function($er){
                    $ciudad = $er->getNombre();
                    return $ciudad. '-' . $er->getDepartamentoRel()->getNombre();
                },
                'label' => 'Ciudad destino:'
            ])
            ->add('productoRel',EntityType::class,[
                'required' => true,
                'class' => TteProducto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Producto:'
            ])
            ->add('vrPeso', IntegerType::class,['required' => true,'label' => 'Valor peso:'])
            ->add('vrUnidad', IntegerType::class,['required' => true,'label' => 'Valor unidad:'])
            ->add('pesoTope', IntegerType::class,['required' => true,'label' => 'Peso tope:'])
            ->add('vrPesoTope', IntegerType::class,['required' => true,'label' => 'Valor peso tope:'])
            ->add('vrPesoTopeAdicional', IntegerType::class,['required' => true,'label' => 'Valor peso adicional:'])
            ->add('minimo', IntegerType::class,['required' => true,'label' => 'Minimo:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TtePrecioDetalle::class,
        ]);
    }
}
