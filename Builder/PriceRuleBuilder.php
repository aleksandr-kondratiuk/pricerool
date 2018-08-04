<?php

namespace SP\PriceRule\Builder;

use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Api\Data\ConditionInterface;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Rule\Condition\Product\Found;
use Magento\SalesRule\Model\Rule\Condition\Product as ConditionProduct;

class PriceRuleBuilder
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
        RuleRepositoryInterface $ruleRepository,
        ConditionInterface $condition,
        Rule $rule,
        Found $found,
        ConditionProduct $conditionProduct
    ) {
        $this->ruleRepository   = $ruleRepository;
        $this->condition        = $condition;
        $this->rule             = $rule;
        $this->found            = $found;
        $this->conditionProduct = $conditionProduct;
    }

    public function createRule()
    {
        $discount = '10';

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


}