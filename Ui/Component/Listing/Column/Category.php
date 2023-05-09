<?php
/**
 * BFound Digital
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://b-found.nl/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    BFD
 * @package     BFD_CatalogSearch
 * @copyright   Copyright (c) 2023 BFound Digital. All rights reserved. (https://b-found.nl/)
 * @license     https://b-found.nl/LICENSE.txt
 */

namespace BFD\CatalogSearch\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Catalog\Model\ProductCategoryList
     */
    private $productCategory;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param \Magento\Catalog\Model\ProductCategoryList $productCategory
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        \Magento\Catalog\Model\ProductCategoryList $productCategory,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->productCategory = $productCategory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Prepare data for the category column
     * @param  array  $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $productId = $item['entity_id'];
                $categoryIds = $this->getCategoryIds($productId);
                $categories = [];
                if (count($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $categoryData = $this->categoryRepository->get($categoryId);
                        $categories[] = $categoryData->getName();
                    }
                }
                $item[$fieldName] = implode(', ', $categories);
            }
        }
        return $dataSource;
    }

    /**
     * Retrieve all the category ids
     *
     * @param integer $productId
     * @return array
     */
    private function getCategoryIds($productId)
    {
        $categoryIds = $this->productCategory->getCategoryIds($productId);
        $_categoryIds = [];
        if ($categoryIds) {
            $_categoryIds = array_unique($categoryIds);
        }
        return $_categoryIds;
    }
}
