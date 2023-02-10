<?php

namespace Pixel\NewsBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\GalleryBundle\Entity\Setting;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingsExtension extends AbstractExtension
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('news_settings', [$this, 'newsSettings']),
        ];
    }

    public function newsSettings()
    {
        return $this->entityManager->getRepository(Setting::class)->findOneBy([]);
    }
}
