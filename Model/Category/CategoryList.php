<?php

namespace Utklasad\AdminProductGridCategoryFilter\Model\Category;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryList implements ArrayInterface
{
    protected $_categoryCollectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->_categoryCollectionFactory = $collectionFactory;
    }

    public function toOptionArray($addEmpty = true)
    {
        $categoryCollection = $this->_categoryCollectionFactory->create()->addAttributeToSelect('name')->setOrder('path', 'ASC');

        $options = [];
        $nameMap = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];
        }

        foreach ($categoryCollection as $category) {
            if ($category->getId() == 1) continue;
            $nameMap[$category->getId()] = $category->getName();
            if (str_contains($category->getPath(), '/')) {
                $ids = explode('/', $category->getPath());
                $names = [];
                foreach ($ids as $id) {
                    if ($id == 1) continue;
                    $names[] = $nameMap[$id]??'';
                }
                $options[] = ['label' => implode(' > ', $names), 'value' => $category->getId()];
            } else {
                $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
            }
        }

        return $options;
    }
}
