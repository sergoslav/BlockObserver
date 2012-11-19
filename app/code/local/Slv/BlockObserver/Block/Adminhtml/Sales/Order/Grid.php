<?php
/**
 * Slv BlockObserver extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Slv BlockObserver module to newer versions in the future.
 * If you wish to customize the Slv BlockObserver module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Slv
 * @package    Slv_BlockObserver
 * @copyright  Copyright (C) 2012 Sergey Petrov <sergoslav@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Example of rewrite block Mage_Adminhtml_Block_Sales_Order_Grid by module Slv_BlockObserver
 *
 * @category   Slv
 * @package    Slv_BlockObserver
 * @subpackage Block
 * @author     Sergey Petrov <sergoslav@gmail.com>
 */
class Slv_BlockObserver_Block_Adminhtml_Sales_Order_Grid extends Mage_Core_Block_Template
{
    /**
     * Function for rewrite method toHtml of block Mage_Adminhtml_Block_Sales_Order_Grid
     *
     * @param $block Mage_Adminhtml_Block_Sales_Order_Grid
     */
    public function Mage_Adminhtml_Block_Sales_Order_Grid($block)
    {
        $block->addColumnAfter(
            'test_column',
            array(
                'header'=> Mage::helper('sales')->__('Test Column'),
                'width' => '80px',
                'type'  => 'text',
                'index' => 'increment_id',
            ),
            'status'
        );
    }
}
