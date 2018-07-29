<?php

namespace SP\PriceRool\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use SP\PriceRool\Builder\Builder;


class Display extends Template
{
    /** @var Session */
    protected $customerSession;

    /** @var StockItemRepository */
    protected $_stockItemRepository;

    /** @var Registry */
    protected $_registry;

    /** @var Builder */
    protected $_builder;

    public function __construct(
        Template\Context $context,
        Session $session,
        StockItemRepository $stockItemRepository,
        Registry $registry,
        Builder $builder,
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
            $this->_builder->createRule();
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
}