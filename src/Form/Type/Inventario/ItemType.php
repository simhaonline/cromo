<?php

namespace App\Form\Type\Inventario;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lineaRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvLinea',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Linea:',
                'required' => false])
            ->add('grupoRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Grupo:',
                'required' => false])
            ->add('subgrupoRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvSubgrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Subgrupo:'
                , 'required' => false])
            ->add('marcaRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvMarca',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Marca:'
                , 'required' => true])
            ->add('impuestoRetencionRel', EntityType::class, [
                'class' => 'App\Entity\General\GenImpuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where("i.codigoImpuestoTipoFk = 'R'")
                        ->orderBy('i.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Retencion:'
                , 'required' => true])
            ->add('nombre', TextType::class, ['required' => true])
            ->add('codigoBarras', TextType::class, ['required' => false])
            ->add('modelo', TextType::class, ['required' => false])
            ->add('referencia', TextType::class, ['required' => false])
            ->add('porcentajeIva', IntegerType::class, ['required' => false])
            ->add('afectaInventario', CheckboxType::class, ['required' => false, 'label' => 'Afecta inventario'])
            ->add('descripcion', TextareaType::class, ['required' => false])
            ->add('stockMinimo', IntegerType::class, ['required' => false])
            ->add('stockMaximo', IntegerType::class, ['required' => false])
            ->add('codigoCuentaVentaFk', TextType::class, ['required' => false])
            ->add('codigoCuentaVentaDevolucionFk', TextType::class, ['required' => false])
            ->add('codigoCuentaCompraFk', TextType::class, ['required' => false])
            ->add('codigoCuentaCompraDevolucionFk', TextType::class, ['required' => false])
            ->add('codigoCuentaCostoFk', TextType::class, ['required' => false])
            ->add('codigoCuentaInventarioFk', TextType::class, ['required' => false])
            ->add('codigoCuentaInventarioTransitoFk', TextType::class, ['required' => false])
            ->add('vrPrecioPredeterminado', IntegerType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvItem'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_item';
    }

}
