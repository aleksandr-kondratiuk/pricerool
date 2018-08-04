<?php

namespace SP\PriceRule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use SP\PriceRule\Builder\PriceRuleBuilder;


class Display extends Template
{
    /** @var Session */
    protected $customerSession;

    /** @var StockItemRepository */
    protected $_stockItemRepository;

    /** @var Registry */
    protected $_registry;

    /** @var PriceRuleBuilder */
    protected $_builder;

    public function __construct(
        Template\Context $context,
        Session $session,
        StockItemRepository $stockItemRepository,
        Registry $registry,
        PriceRuleBuilder $builder,
        array $data = []
    ) {
        $this->customerSession= $session;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_registry = $registry;
        $this->_builder = $builder;
        parent::__construct($context, $data);
    }

    public function _toHtml()
    {
        if ($this->customerSession->isLoggedIn()){
//            $this->_builder->createRule();
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }


    public function isOutOfStock()
    {
        $product = $this->getCurrentProduct();
        if($product){
            $stockItem = $this->getStockItem($product->getId());
            return !$stockItem->getIsInStock();
        }
        return '';
    }
}
