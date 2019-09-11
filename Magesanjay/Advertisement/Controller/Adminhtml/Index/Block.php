<?php

namespace Magesanjay\Advertisement\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Cms\Model\BlockFactory;

class Block extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magesanjay_Advertisement::ajax_category_block';

    /**
     * @var JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var BlockFactory $blockFactory
     */
    protected $_blockFactory;
    
    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        BlockFactory $blockFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_blockFactory = $blockFactory;
        parent::__construct($context);
    }

    /**
     * block action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultData = [['value' => '0', 'label' => __('[ SELECT BLOCK ]')]];
        if ($this->getRequest()->isAjax()) {
            $type = $this->getRequest()->getParam('type');
            if ($type != \Magesanjay\Advertisement\Block\Adminhtml\Options::TYPE_NONE) {
                switch ($type) {
                    case \Magesanjay\Advertisement\Block\Adminhtml\Options::TYPE_BLOCK:
                        $blockObject = $this->_blockFactory->create();
                        foreach ($blockObject->getCollection() as $block)
                            $resultData[] = ['value' => $block->getId(), 'label' => $block->getTitle()];
                        break;
                }
            }
        }
        return $result->setData($resultData);
    }
}
