<?php

namespace Magesanjay\Advertisement\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Registry;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Layout extends \Magento\Framework\App\Helper\AbstractHelper
{
    const GRID_ENABLE_PATH = 'advertisement/general/enabled';
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var \Zend_Filter_Interface
     */
    protected $templateProcessor;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Registry $coreRegistry
     * @param BlockFactory $blockFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param \Zend_Filter_Interface $templateProcessor
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Registry $coreRegistry,
        BlockFactory $blockFactory,
        ScopeConfigInterface $scopeConfig,
        \Zend_Filter_Interface $templateProcessor
    ) {
        $this->_storeManager = $storeManager;
        $this->_coreRegistry = $coreRegistry;
        $this->_blockFactory = $blockFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->templateProcessor = $templateProcessor;
        parent::__construct($context);
    }

    /**
     * get listing block based on layout
     *
     * @return array
     */
    public function getListingBlockItems()
    {
        $loadedData = [];

        $blockLayout = $this->_getCurrentCategory()->getBlockLayout();

        if (isset($blockLayout) && $blockLayout != "") {
            $blockLayout = json_decode($blockLayout, true);

            $sortOrders = array_map(function ($element) {
                return $element['sort_order'];
            }, $blockLayout);

            foreach ($blockLayout as $block) {
                $sortOrder = 0;
                if (isset($block['sort_order']) && $block['sort_order'] != "" && $block['sort_order'] != "0")
                    $sortOrder = intval($block['sort_order']);

                if ($sortOrder == 0 || array_key_exists($sortOrder, $loadedData))
                    $sortOrder = $this->getSortOrder($loadedData, min($sortOrders));

                $loadedData[$sortOrder] = [
                    'type' => $block['select_type'],
                    'block' => $block['select_block']
                ];
            }
        }
        return $loadedData;
    }

    /**
     * get unique key
     *
     * @param array $loadedData
     * @param int $i
     * @return int
     */
    public function getSortOrder($loadedData, $i)
    {
        if ($i == 0) $i = 1;

        while (array_key_exists($i, $loadedData)) {
            $i++;
        }
        return $i ;
    }

    /**
     * get current category object
     *
     * @return \Mage\Catalog\Model\Category
     */
    protected function _getCurrentCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }

    /**
     * get the actual filter data
     *
     * @param HTML string
     * @return HTML output
     */
    public function filterOutputHtml($blockArray, $layout)
    {
        if ($blockArray['type'] == \Magesanjay\Advertisement\Block\Adminhtml\Options::TYPE_BLOCK)
            return $this->_getBlockHtml($blockArray['block'], $layout);
    }

    /**
     * Retrieve block content
     *
     * @param string $identifier
     * @return string
     */
    protected function _getBlockHtml($identifier, $layout)
    {
        $blockObject = $this->_blockFactory->create();
        $blockObject->load($identifier);
        if ($blockObject->getId())
            return $this->templateProcessor->filter($blockObject->getContent());

        return '';
    }

    /**
     * Retrieve status of config
     *
     * @return int
     */
    public function isActive()
    {
        return $this->_scopeConfig->getValue(self::GRID_ENABLE_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
