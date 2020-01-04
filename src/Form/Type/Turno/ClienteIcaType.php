<?php


namespace App\Form\Type\Turno;


use App\Entity\General\GenCiudad;
use App\Entity\Turno\TurClienteIca;
use App\Entity\Turno\TurConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteIcaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadRel', EntityType::class, array(
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => '',
                'required' => true))
            ->add('conceptoServicioRel', EntityType::class, array(
                'class' => TurConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cs')
                        ->where('cs.tipo = 1')
                        ->orderBy('cs.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false))
            ->add('codigoServicioErp', TextType::class, array("required" => false))
            ->add('tarIca', NumberType::class, array("required" => true))
            ->add('porIca', NumberType::class, array("required" => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurClienteIca::class,
        ]);
    }

}