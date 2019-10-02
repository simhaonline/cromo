<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiquidacionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("codigoLiquidacionTipoPk", TextType::class, ['required'=>true])
            ->add("nombre", TextType::class, ['required'=>true])
            ->add("consecutivo", TextType::class, ['required'=>true])
            ->add("codigoConceptoCesantiaFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoInteresFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoCesantiaAnteriorFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoInteresAnteriorFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoPrimaFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoVacacionFk", TextType::class, ['required'=>true])
            ->add("codigoConceptoIndemnizacionFk", TextType::class, ['required'=>true])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLiquidacionTipo::class,
        ]);
    }
}