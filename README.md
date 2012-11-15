BlockObserver Magento module
=======

BlockObserver is a Magento module for rewrite blocs by event of method _toHtml using observer, without actually rewriting blocks.

Installation
------------
### Standard installation
Copy module to magento directory:
* `app/code/local/Slv/BlockObserver` to `app/code/local/Slv/BlockObserver`
* `app/etc/modules/Slv_BlockObserver.xml` to `app/etc/modules/Slv_BlockObserver.xml`

### Using modman
        $ modman clone git@github.com:sergoslav/BlockObserver.git


Using
------------

* Add to `.../Slv/BlockObserver/etc/config.xml` in section `<block_events>...</block_events>` data by rewrite block:
``````xml
<block_events>

    <!--Example rewrite of block Mage_Adminhtml_Block_Sales_Order_Grid:-->
    <Mage_Adminhtml_Block_Sales_Order_Grid>
        <observers>
            <type>singleton</type>
            <block>blockObserver/adminhtml_sales_order_grid</block>
            <method>Mage_Adminhtml_Block_Sales_Order_Grid</method>
            <parent>true</parent>
        </observers>
    </Mage_Adminhtml_Block_Sales_Order_Grid>
</block_events>
``````
* Create block for add observer for method _toHtml() of block

For example:
```````php
class Slv_BlockObserver_Block_Adminhtml_Sales_Order_Grid extends Mage_Core_Block_Template
{
    /**
     * Function for rewrite method _toHtml of block Mage_Block
     *
     * @param $block Mage_Adminhtml_Block_Sales_Order_Grid
     */
    public function Mage_Adminhtml_Block_Sales_Order_Grid($block)
    {
        $block->addColumn('additional_column', array(
            'header'=> Mage::helper('sales')->__('Additional Column'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));
    }
}
```````