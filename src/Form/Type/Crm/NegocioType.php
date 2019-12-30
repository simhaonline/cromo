<?php


namespace App\Form\Type\Crm;


use App\Entity\Crm\CrmFase;
use App\Entity\Crm\CrmNegocio;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NegocioType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCierre', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaNegocio', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('valor', NumberType::class, ['required' => true, 'label' => 'Codigo fase:'])
            ->add('comentario', TextareaType::class, ['required' => false, 'label' => 'Codigo fase:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Codigo fase:'])
            ->add('codigoClienteFk', TextType::class, ['required' => true, 'label' => 'Codigo Cliente:'])
            ->add('codigoContactoFk', TextType::class, ['required' => true, 'label' => 'Codigo Contacto:'])
            ->add('faseRel', EntityType::class, [
                'class' => 'App\Entity\Crm\CrmFase',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.codigoFasePk', 'ASC');
                },
                'required' => true,
                'choice_label' => 'nombre',
                'placeholder'=>''
            ])
            ->add('estadoCerrado',      CheckboxType::class, ['required' => false])
            ->add('estadoGanado',       CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CrmNegocio::class,
        ]);
    }

}