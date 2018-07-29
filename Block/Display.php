<?php
/**
 * @author Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2017, Pavel Usachev
 */

namespace SP\PriceRool\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;


class Display extends Template
{
    protected $customerSession;
    protected $_stockItemRepository;
    protected $_registry;

    public function __construct(
        Template\Context $context,
        Session $session,
        StockItemRepository $stockItemRepository,
        Registry $registry,
        array $data = []
    ) {
        $this->customerSession= $session;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    public function _toHtml()
    {
        if ($this->customerSession->isLoggedIn()){
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
