<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_AdminMonitoring
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2013 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * Helper class
 *
 * @category FireGento
 * @package  FireGento_AdminMonitoring
 * @author   FireGento Team <team@firegento.com>
 */
class FireGento_AdminMonitoring_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * SCOPES
     */
    const SCOPE_GLOBAL = 0;
    const SCOPE_WEBSITE = 1;
    const SCOPE_STORE = 2;
    const SCOPE_STORE_VIEW = 3;

    /**
     * ACTIONS
     */
    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    /**
     * STATUS
     */
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

    /**
     * Retrieve the attribute type by provided class name
     *
     * @param  string $className Class Name
     * @return string|bool Attribute Type or false
     */
    public function getAttributeTypeByClassName($className)
    {
        /** @var Mage_Eav_Model_Resource_Entity_Type_Collection $typesCollection */
        $typesCollection = Mage::getModel('eav/entity_type')->getCollection();
        foreach ($typesCollection->getItems() as $type) {
            /* @var Mage_Eav_Model_Entity_Type $type */
            if ($type->getEntityModel() && Mage::getModel($type->getEntityModel()) instanceof $className) {
                return $type->getEntityTypeCode();
            }
        }

        return false;
    }

    /**
     * Retrieve the attribute name by provided class name and attribute code
     *
     * @param  string $className     Class Name
     * @param  string $attributeCode Attribute Code
     * @return string Attribute Name
     */
    public function getAttributeNameByTypeAndCode($className, $attributeCode)
    {
        $attributeName = $attributeCode;

        $type = $this->getAttributeTypeByClassName($className);
        if ($type) {
            /* @var Mage_Eav_Model_Entity_Attribute $attribute */
            $attribute = Mage::getModel('eav/entity_attribute')->loadByCode($type, $attributeCode);
            if ($attribute->getFrontendLabel()) {
                $attributeName = $attribute->getFrontendLabel();
            }
        }

        return $attributeName;
    }

    /**
     * Retrieve the row url for the given history row
     *
     * @param  FireGento_AdminMonitoring_Model_History $row
     * @return string
     */
    public function getRowUrl(FireGento_AdminMonitoring_Model_History $row)
    {
        $transport = new Varien_Object();
        Mage::dispatchEvent('firegento_adminmonitoring_rowurl', array('history' => $row, 'transport' => $transport));

        return $transport->getRowUrl();
    }
}
