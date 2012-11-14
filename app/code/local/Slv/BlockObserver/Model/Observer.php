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
 * Observer model
 *
 * @category   Slv
 * @package    Slv_BlockObserver
 * @subpackage Model
 * @author     Sergey Petrov <sergoslav@gmail.com>
 */
class Slv_BlockObserver_Model_Observer extends Mage_Core_Model_Abstract
{
    protected static $_observers = null;
    protected static $_observersKeys = array();

    public function __construct()
    {
        self::$_observersKeys = self::getKeysBlockObservers();
    }

    /**
     * Get data by blocks observers
     *
     * @param string $key
     * @return array|bool
     */
    public static function getBlockObserver($key)
    {
        if (self::$_observers === null) {
            $cacheId = 'SLV_CONFIG_BLOCK_OBSERVERS';
            $cacheData = Mage::app()->getCache()->load($cacheId);
            if ($cacheData !== false) {
                $result = unserialize($cacheData);
            } else {
                $entities = Mage::getConfig()->getNode('block_events', 'global');
                $result = array();
                foreach ((array)$entities as $key => $value) {
                    $result[$key] = (array)$value->observers;
                }
                Mage::app()->getCache()->save(serialize($result), $cacheId, array('CONFIG'));
            }
            self::$_observers = $result;
        }
        try {
            return self::$_observers[$key];
        } catch (Exception $e) {
            Mage::log($e->getMessage());

            return false;
        }
    }

    /**
     * Get only keys of observers
     *
     * @return array|mixed
     */
    public static function getKeysBlockObservers()
    {
        $cacheId = 'SLV_CONFIG_BLOCK_OBSERVERS_KEYS';
        $cacheData = Mage::app()->getCache()->load($cacheId);
        if ($cacheData !== false) {
            $result = unserialize($cacheData);
        } else {
            $entities = Mage::getConfig()->getNode('block_events', 'global');
            $result = array();
            $result = array_keys((array)$entities);
            Mage::app()->getCache()->save(serialize($result), $cacheId, array('CONFIG'));
        }

        return $result;
    }

    /**
     * Observer of adminhtml_block_html_before
     *
     * @param $observer
     * @return bool
     */
    public function runBlockObserver($observer)
    {
        if (in_array(get_class($observer->getEvent()->getBlock()), self::$_observersKeys)) {
            $blockObserver = $this->getBlockObserver(get_class($observer->getEvent()->getBlock()));
        } elseif (in_array(get_parent_class($observer->getEvent()->getBlock()), self::$_observersKeys)) {
            $blockObserver = $this->getBlockObserver(get_parent_class($observer->getEvent()->getBlock()));
            if (!$blockObserver['parent']) {
                return false;
            }
        } else {
            return false;
        }

        switch ($blockObserver['type']) {
            case 'singleton':
                {
                    Mage::getBlockSingleton($blockObserver['block'])->$blockObserver['method'](
                        $observer->getEvent()->getBlock()
                    );
                }
                break;
            default:
                {
                    Mage::getBlock($blockObserver['block'])->$blockObserver['method'](
                        $observer->getEvent()->getBlock()
                    );
                }
                break;
        }

    }
}
