<?php


namespace App\Form\Type\Turno;


use App\Entity\Financiero\FinCentroCosto;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\Turno\TurProgramador;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoAdicional;
use App\Entity\Turno\TurPuestoTipo;
use App\Entity\Turno\TurSalario;
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

class PuestoAdicionalType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conceptoRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.adicional = 1')
                        ->andWhere('c.operacion = 1')
                        ->orderBy('c.codigoConceptoPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('valor', TextType::class)
            ->add('incluirDescanso', CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurPuestoAdicional::class,
        ]);
    }

}