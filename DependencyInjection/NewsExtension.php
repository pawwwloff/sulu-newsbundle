<?php

namespace Pixel\NewsBundle\DependencyInjection;

use Pixel\NewsBundle\Admin\NewsAdmin;
use Pixel\NewsBundle\Entity\News;
use Sulu\Bundle\PersistenceBundle\DependencyInjection\PersistenceExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

class NewsExtension extends Extension implements PrependExtensionInterface
{
    use PersistenceExtensionTrait;

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig(
                'sulu_admin',
                [
                    'forms' => [
                        'directories' => [
                            __DIR__ . '/../Resources/config/forms',
                        ],
                    ],
                    'lists' => [
                        'directories' => [
                            __DIR__ . '/../Resources/config/lists',
                        ],
                    ],
                    'resources' => [
                        'news' => [
                            'routes' => [
                                'detail' => 'news.get_news',
                                'list' => 'news.cget_news',
                            ],
                        ],
                        'news_settings' => [
                            'routes' => [
                                'detail' => 'news.get_news-settings'
                            ]
                        ]
                    ],
                    'field_type_options' => [
                        'selection' => [
                            'news_selection' => [
                                'default_type' => 'list_overlay',
                                'resource_key' => News::RESOURCE_KEY,
                                'view' => [
                                    'name' => NewsAdmin::EDIT_FORM_VIEW,
                                    'result_to_view' => [
                                        'id' => 'id',
                                    ],
                                ],
                                'types' => [
                                    'list_overlay' => [
                                        'adapter' => 'table',
                                        'list_key' => News::LIST_KEY,
                                        'display_properties' => ['title'],
                                        'icon' => 'su-newspaper',
                                        'label' => 'news',
                                        'overlay_title' => 'news.newsList',
                                    ],
                                ],
                            ],
                        ],
                        'single_selection' => [
                            'single_news_selection' => [
                                'default_type' => 'list_overlay',
                                'resource_key' => News::RESOURCE_KEY,
                                'view' => [
                                    'name' => NewsAdmin::EDIT_FORM_VIEW,
                                    'result_to_view' => [
                                        'id' => 'id',
                                    ],
                                ],
                                'types' => [
                                    'list_overlay' => [
                                        'adapter' => 'table',
                                        'list_key' => News::LIST_KEY,
                                        'display_properties' => ['title'],
                                        'icon' => 'su-newspaper',
                                        'empty_text' => 'news.emptyNews',
                                        'overlay_title' => 'card.cardList',
                                    ],
                                    'auto_complete' => [
                                        'display_property' => 'title',
                                        'search_properties' => ['title'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            );
        }
        if ($container->hasExtension('sulu_search')) {
            $container->prependExtensionConfig(
                'sulu_search',
                [
                    'indexes' => [
                        'news' => [
                            'name' => 'news.searchName',
                            'icon' => 'su-newspaper',
                            'view' => [
                                'name' => NewsAdmin::EDIT_FORM_VIEW,
                                'result_to_view' => [
                                    'id' => 'id',
                                    'locale' => 'locale',
                                ],
                            ],
                            'security_context' => News::SECURITY_CONTEXT,
                        ],
                    ],
                    'website' => [
                        "indexes" => [
                            "news",
                        ],
                    ],
                ]
            );
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loaderYaml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loaderYaml->load('services.yaml');
        //$this->configurePersistence($config['objects'], $container);
    }

}
