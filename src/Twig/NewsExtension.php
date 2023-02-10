<?php

namespace Pixel\NewsBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\NewsBundle\Entity\News;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NewsExtension extends AbstractExtension
{
    private EntityManagerInterface $entityManager;

    private Environment $environment;

    private RequestStack $request;

    public function __construct(EntityManagerInterface $entityManager, Environment $environment, RequestStack $request)
    {
        $this->entityManager = $entityManager;
        $this->environment = $environment;
        $this->request = $request;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("get_latest_news_html", [$this, "getLatestNewsHtml"], [
                "is_safe" => ["html"],
            ]),
            new TwigFunction("get_latest_news", [$this, "getLatestNews"]),
        ];
    }

    public function getLatestNewsHtml(int $limit = 3, $locale = 'fr')
    {
        $news = $this->entityManager->getRepository(News::class)->findByFilters([], 0, $limit, $limit, $locale);
        ;
        return $this->environment->render("@News/twig/news.html.twig", [
            "news" => $news,
        ]);
    }

    public function getLatestNews(int $limit = 3, $locale = 'fr')
    {
        return $this->entityManager->getRepository(News::class)->findByFilters([], 0, $limit, $limit, $locale);
    }
}
