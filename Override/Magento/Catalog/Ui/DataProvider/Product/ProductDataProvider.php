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

namespace BFD\CatalogSearch\Override\Magento\Catalog\Ui\DataProvider\Product;

class ProductDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Add the category id to filter
     *
     * @param \Magento\Framework\Api\Filter $filter
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() == 'category_id') {
            $this->getCollection()->addCategoriesFilter(['in' => $filter->getValue()]);
        } elseif (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }
}
