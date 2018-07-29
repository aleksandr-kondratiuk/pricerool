<?php
/**
 * @author Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2017, Pavel Usachev
 */

namespace SP\PriceRool\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;

class Display extends Template
{
    protected $customerSession;

    public function __construct(
        Template\Context $context,
        Session $session,
        array $data = []
    ) {
        $this->customerSession= $session;
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

}
