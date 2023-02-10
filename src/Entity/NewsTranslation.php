<?php

namespace Pixel\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news_translation")
 * @ORM\Entity(repositoryClass="Pixel\NewsBundle\Repository\NewsRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class NewsTranslation implements AuditableInterface
{
    use AuditableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Pixel\NewsBundle\Entity\News", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private News $news;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private string $locale;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private string $routePath;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Serializer\Expose()
     */
    private ?bool $isPublished;

    /**
     * @ORM\Column(type="date_immutable", nullable=true)
     * @Serializer\Expose()
     */
    private ?\DateTimeImmutable $publishedAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Serializer\Expose()
     */
    private ?array $seo = null;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Expose()
     */
    private array $content;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Serializer\Expose()
     */
    private ?array $excerpt = null;

    public function __construct(News $news, string $locale)
    {
        $this->news = $news;
        $this->locale = $locale;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNews(): News
    {
        return $this->news;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * return self
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getRoutePath(): string
    {
        return $this->routePath ?? '';
    }

    /**
     * @return self
     */
    public function setRoutePath(string $routePath): void
    {
        $this->routePath = $routePath;
    }

    /**
     * @return bool
     */
    public function isPublished(): ?bool
    {
        return $this->isPublished ?? '';
    }

    public function setIsPublished(?bool $isPublished): void
    {
        $this->isPublished = $isPublished;
        if ($isPublished === true) {
            $this->setPublishedAt(new \DateTimeImmutable());
        } else {
            $this->setPublishedAt(null);
        }
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getSeo(): ?array
    {
        return $this->seo;
    }

    public function setSeo(?array $seo): void
    {
        $this->seo = $seo;
    }

    /**
     * @return array
     */
    public function getContent(): ?array
    {
        return $this->content ?? [];
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }

    public function getExcerpt(): ?array
    {
        return $this->excerpt;
    }

    public function setExcerpt(?array $excerpt): void
    {
        $this->excerpt = $excerpt;
    }
}
