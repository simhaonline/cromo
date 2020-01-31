<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurOperacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoOperacionPk', TextType::class, ['required' => true ])
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('nombreCorto', TextType::class, ['required' => true, 'label'=>'Nombre corto'])
            ->add('clienteRel', EntityType::class, [
                'class' => TurCliente::class,
                'label' => 'Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoClientePk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombreCorto',
                'placeholder' => 'TODOS',
            ])
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurOperacion::class,
        ]);
    }

}
