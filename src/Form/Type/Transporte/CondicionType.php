<?php
namespace App\Form\Type\Transporte;
use App\Entity\Transporte\TteCondicion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CondicionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('precioRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TtePrecio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Precio:'
            ])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('porcentajeManejo',NumberType::class,['required' => true,'label' => 'Porcentaje:'])
            ->add('manejoMinimoUnidad',NumberType::class,['required' => true,'label' => 'Unidad minima:'])
            ->add('manejoMinimoDespacho',NumberType::class,['required' => true,'label' => 'Despacho minimo:'])
            ->add('precioPeso', CheckboxType::class, array('required'  => false))
            ->add('precioUnidad', CheckboxType::class, array('required'  => false))
            ->add('precioAdicional', CheckboxType::class, array('required'  => false))
            ->add('descuentoPeso',NumberType::class,['required' => true,'label' => 'Descuento peso:'])
            ->add('descuentoUnidad',NumberType::class,['required' => true,'label' => 'Descuento unidad:'])
            ->add('pesoMinimo',NumberType::class,['required' => true,'label' => 'Peso minimo:'])
            ->add('permiteRecaudo', CheckboxType::class, ['required' => false,'label' => 'Permite recaudo'])
            ->add('precioGeneral', CheckboxType::class, ['required' => false,'label' => 'Precio general'])
            ->add('redondearFlete', CheckboxType::class, ['required' => false,'label' => 'Redondear flete'])
            ->add('limitarDescuentoReexpedicion', CheckboxType::class, ['required' => false,'label' => 'Limitar descuento reexpedicion'])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCondicion::class,
        ]);
    }
}
