<?php

namespace SP\PriceRool\Builder;

use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Api\Data\ConditionInterface;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Rule\Condition\Product\Found;
use Magento\SalesRule\Model\Rule\Condition\Product as ConditionProduct;

class Builder extends \Magento\Rule\Model\AbstractModel
{
    /**
     * SKU argument
     */
    const SKU_ARGUMENT = 'sku';
    /**admin
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;
    /**
     * @var ConditionInterface
     */
    protected $condition;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var Found
     */
    protected $found;

    /**
     * @var ConditionProduct
     */
    protected $conditionProduct;

    public function __construct(
//        \Magento\Framework\App\State $appState,
        RuleRepositoryInterface $ruleRepository,
        ConditionInterface $condition,
        Rule $rule,
        Found $found,
        ConditionProduct $conditionProduct
    ) {
//        $appState->setAreaCode('admin');
        $this->ruleRepository   = $ruleRepository;
        $this->condition        = $condition;
        $this->rule             = $rule;
        $this->found            = $found;
        $this->conditionProduct = $conditionProduct;
    }


    public function createRule()
    {
        $discount = '10';
        $this->getListProductIdsInRule();

        $this->rule->setName('JoshuaFlood_DiscountGenerator')
            ->setDescription('Do not delete! Current product sku - ' .'24-MB01')
            ->setFromDate(NULL)
            ->setToDate(NULL)
            ->setUsesPerCustomer('0')
            ->setCustomerGroupIds(array('0','1','2','3',))
            ->setIsActive('1')
            ->setStopRulesProcessing('0')
            ->setIsAdvanced('1')
            ->setProductIds(NULL)
            ->setSortOrder('1')
            ->setSimpleAction('by_percent')
            ->setDiscountAmount($discount)
            ->setDiscountQty(NULL)
            ->setDiscountStep('0')
            ->setSimpleFreeShipping('0')
            ->setApplyToShipping('0')
            ->setTimesUsed('0')
            ->setIsRss('0')
            ->setWebsiteIds(array('1',))
            ->setCouponType('1')
            ->setCouponCode('AAA')
            ->setUsesPerCoupon(NULL);

        $item_found = $this->found
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product\Found')
            ->setValue(1)
            ->setAggregator('all');
        $this->rule->getConditions()->addCondition($item_found);
        $conditions = $this->conditionProduct
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setAttribute('sku')
            ->setOperator('==')
            ->setValue('24-MB01');
//            ->setValue($sku);
        $item_found->addCondition($conditions);

        $actions = $this->conditionProduct
            ->setType('Magento\SalesRule\Model\Rule\Condition\Product')
            ->setAttribute('sku')
            ->setOperator('==')
            ->setValue('24-MB01');
//            ->setValue($sku);
        $this->rule->getActions()->addCondition($actions);

        $this->rule->save();
    }


    protected $_productIds;
    /**
     * Get array of product ids which are matched by rule
     *
     * @return array
     */
    public function getListProductIdsInRule()
    {
        $productCollection = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Catalog\Model\ResourceModel\Product\Collection'
        );
        $productFactory = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Catalog\Model\ProductFactory'
        );
        $this->_productIds = [];
        $this->setCollectedAttributes([]);
        $this->getConditions()->collectValidatedAttributes($productCollection);
        \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Framework\Model\ResourceModel\Iterator'
        )->walk(
            $this->_productCollection->getSelect(),
            [[$this, 'callbackValidateProduct']],
            [
                'attributes' => $this->getCollectedAttributes(),
                'product' => $productFactory->create()
            ]
        );
        return $this->_productIds;
    }
    /**
     * Callback function for product matching
     *
     * @param array $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);
        $websites = $this->_getWebsitesMap();
        foreach ($websites as $websiteId => $defaultStoreId) {
            $product->setStoreId($defaultStoreId);
            if ($this->getConditions()->validate($product)) {
                $this->_productIds[] = $product->getId();
            }
        }
    }
    /**
     * Prepare website map
     *
     * @return array
     */
    protected function _getWebsitesMap()
    {
        $map = [];
        $websites = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Store\Model\StoreManagerInterface'
        )->getWebsites();
        foreach ($websites as $website) {
            // Continue if website has no store to be able to create catalog rule for website without store
            if ($website->getDefaultStore() === null) {
                continue;
            }
            $map[$website->getId()] = $website->getDefaultStore()->getId();
        }
        return $map;
    }

    /**
     * Getter for rule combine conditions instance
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditionsInstance()
    {
        // TODO: Implement getConditionsInstance() method.
    }

    /**
     * Getter for rule actions collection instance
     *
     * @return \Magento\Rule\Model\Action\Collection
     */
    public function getActionsInstance()
    {
        // TODO: Implement getActionsInstance() method.
    }
}