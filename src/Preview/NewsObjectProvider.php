<?php

declare(strict_types=1);

namespace Pixel\NewsBundle\Preview;

use Pixel\NewsBundle\Entity\News;
use Pixel\NewsBundle\Repository\NewsRepository;
use Sulu\Bundle\CategoryBundle\Category\CategoryManagerInterface;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Bundle\PreviewBundle\Preview\Object\PreviewObjectProviderInterface;

class NewsObjectProvider implements PreviewObjectProviderInterface
{
    private NewsRepository $newsRepository;

    private MediaManagerInterface $mediaManager;

    private CategoryManagerInterface $categoryManager;

    public function __construct(
        NewsRepository $newsRepository,
        MediaManagerInterface $mediaManager,
        CategoryManagerInterface $categoryManager
    ) {
        $this->newsRepository = $newsRepository;
        $this->mediaManager = $mediaManager;
        $this->categoryManager = $categoryManager;
    }

    public function getObject($id, $locale): News
    {
        return $this->newsRepository->find((int) $id);
    }

    public function getId($object): int
    {
        return $object->getId();
    }

    /**
     * @param News $object
     */
    public function setValues($object, $locale, array $data)
    {
        $coverId = $data['cover']['id'] ?? null;
        $categoryId = (isset($data['category']['id'])) ? $data['category']['id'] : $data['category'];
        $isPublished = $data['isPublished'] ?? null;
        $seo = (isset($data['ext']['seo'])) ? $data['ext']['seo'] : null;

        $object->setTitle($data['title']);
        $object->setRoutePath($data['routePath']);
        $object->setIsPublished($isPublished);
        $object->setCover($coverId ? $this->mediaManager->getEntityById($coverId) : null);
        $object->setCategory($this->categoryManager->findById($categoryId));
        $object->setContent($data['content']);
        $object->setSeo($seo);
    }

    public function setContext($object, $locale, array $context)
    {
        if (\array_key_exists('template', $context)) {
            $object->setStructureType($context['template']);
        }

        return $object;
    }

    /**
     * @param News $object
     * @return string
     */
    public function serialize($object)
    {
        if (!$object->getTitle()) {
            $object->setTitle('');
        }

        return serialize($object);
    }

    public function deserialize($serializedObject, $objectClass)
    {
        return unserialize($serializedObject);
    }

    public function getSecurityContext($id, $locale): ?string
    {
    }
}
