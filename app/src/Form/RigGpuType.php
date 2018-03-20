<?php declare(strict_types = 1);

namespace App\Form;

use App\Entity\RigGpu;
use App\Entity\VgaBios;
use App\Entity\VgaBiosIndex;
use App\Repository\VgaBiosIndexRepository;
use App\Repository\VgaBiosRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RigGpuType extends AbstractType
{
    private $vgaBiosIndexRepository;
    private $vgaBiosRepository;

    public function __construct(VgaBiosRepository $vgaBiosRepository, VgaBiosIndexRepository $vgaBiosIndexRepository)
    {
        $this->vgaBiosRepository = $vgaBiosRepository;
        $this->vgaBiosIndexRepository = $vgaBiosIndexRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var VgaBiosIndex[] $vgaBiosIndexList */
        $vgaBiosIndexList = $this->vgaBiosIndexRepository->findAll();
        $vgaBiosIndexMap = [];
        foreach ($vgaBiosIndexList as $vgaBiosIndex) {
            $vgaBiosIndexMap[$vgaBiosIndex->getVgaBiosId()] = $vgaBiosIndex;
        }
        $vgaBiosList = $this->vgaBiosRepository->findAll();

        $builder
            ->add('vgaBios', ChoiceType::class, [
                'choices' => $vgaBiosList,
                'choice_value' => 'id',
                'choice_label' => function (VgaBios $vgaBios) use ($vgaBiosIndexMap) {
                    $vgaBiosIndex = $vgaBiosIndexMap[$vgaBios->getId()] ?? null;
                    return $vgaBiosIndex ? $vgaBiosIndex->getFullName() : $vgaBios->getId();
                }
            ])
            ->add('count', IntegerType::class, [
            ])
            ->add('powerLimitPercentage', PercentType::class, [
                'required' => false,
            ])
            ->add('gpuClock', IntegerType::class, [
                'required' => false,
            ])
            ->add('gpuVoltage', IntegerType::class, [
                'required' => false,
            ])
            ->add('memoryClock', IntegerType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add',
                'attr' => ['class' => 'btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RigGpu::class,
        ]);
    }
}
