<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Flight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schedule', TimeType::class,[
                'hours'=> range(6,22),
                'label'=> 'Horaire du vol'
            ])
            ->add('price', NumberType::class, [
                'required'=> false,
                'label'=> 'Prix du vol'
            ])
            ->add('reduction', CheckboxType::class, [
                'required'=> false,
                'label'=> 'Réduction de 5%'
            ])
            ->add('seat', IntegerType::class, [
                'required'=> false,
                'label'=> 'Nombre de places'
            ])
            ->add('arrival', EntityType::class, [
                'class'=> City::class,
                'choice_label'=>'name',
                'label'=>'Arrivée'
            ])
            ->add('departure', EntityType::class, [
                'class'=> City::class,
                'choice_label'=>'name',
                'label'=>'Départ'
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
        ]);
    }
}
