<?php

declare(strict_types=1);

namespace Pixel\NewsBundle\Content;

use Doctrine\ORM\EntityManagerInterface;
use Pixel\NewsBundle\Entity\News;
use Sulu\Component\Serializer\ArraySerializerInterface;
use Sulu\Component\SmartContent\DataProviderResult;
use Sulu\Component\SmartContent\ItemInterface;
use Sulu\Component\SmartContent\Orm\BaseDataProvider;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class NewsDataProvider extends BaseDataProvider
{
    private RequestStack $requestStack;

    private EntityManagerInterface $entityManager;

    public function __construct(DataProviderRepositoryInterface $repository, ArraySerializerInterface $serializer, RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($repository, $serializer);
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function getConfiguration()
    {
        if (!$this->configuration) {
            $this->configuration = self::createConfigurationBuilder()
                ->enableLimit()
                ->enablePagination()
                ->enablePresentAs()
                ->enableCategories()
                ->enableSorting([
                    [
                        'column' => 'translation.title',
                        'title' => 'news.title',
                    ],
                    [
                        'column' => 'translation.publishedAt',
                        'title' => 'news.publishedAt',
                    ],
                ])
                ->getConfiguration();
        }

        return $this->configuration;
    }

    public function resolveResourceItems(
        array $filters,
        array $propertyParameter,
        array $options = [],
        $limit = null,
        $page = 1,
        $pageSize = null
    ) {
        $locale = $options['locale'];
        $request = $this->requestStack->getCurrentRequest();
        $options['page'] = $request->get('p');
        $news = $this->entityManager->getRepository(News::class)->findByFilters($filters, $page, $pageSize, $limit, $locale, $options);
        return new DataProviderResult($news, $this->entityManager->getRepository(News::class)->hasNextPage($filters, $page, $pageSize, $limit, $locale, $options));
    }

    /**
     * Decorates result as data item.
     *
     * @return ItemInterface[]
     */
    protected function decorateDataItems(array $data)
    {
        return array_map(
            function ($item) {
                return new NewsDataItem($item);
            },
            $data
        );
    }

    /**
     * Returns additional options for query creation.
     *
     * @param PropertyParameter[] $propertyParameter
     *
     * @return array
     */
    protected function getOptions(array $propertyParameter, array $options = [])
    {
        $request = $this->requestStack->getCurrentRequest();
        $result = [
            'page' => $request->get('p'),
            'type' => $request->get('type'),
        ];

        return array_filter($result);
    }
}
