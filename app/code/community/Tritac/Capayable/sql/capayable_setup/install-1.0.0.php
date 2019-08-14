<?php
/**
 * Tritac_Capayable setup script
 *
 * @category    Tritac
 * @package     Tritac_Capayable
 * @copyright   Copyright (c) 2014 Tritac (http://www.tritac.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$installer = $this;

/* @var $installer Tritac_Capayable_Model_Resource_Setup */
$installer->startSetup();

/**
 * Create table 'capayable_data'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('capayable/customer'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Entity Id')
    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Customer Email')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Customer Id')
    ->addColumn('customer_lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
    ), 'Customer Lastname')
    ->addColumn('customer_middlename', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
    ), 'Customer Initials')
    ->addColumn('customer_gender', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'Customer Gender')
    ->addColumn('customer_dob', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Customer Dob')
    ->addColumn('street', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
    ), 'Street')
    ->addColumn('house_number', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
    ), 'House Number')
    ->addColumn('house_suffix', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
    ), 'House Number')
    ->addColumn('postcode', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
    ), 'Postcode')
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
    ), 'City')
    ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(
    ), 'Country Id')
    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
    ), 'Telephone')
    ->addColumn('fax', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
    ), 'Fax')
    ->addColumn('is_corporation', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Corporation')
    ->addColumn('corporation_name', Varien_Db_Ddl_Table::TYPE_TEXT, 150, array(
    ), 'Corporation Name')
    ->addColumn('is_sole', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
    ), 'Is Sole Proprietor')
    ->addColumn('coc_number', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
    ), 'Chamber of Commerce number')
    ->addIndex($installer->getIdxName('capayable/customer', array('customer_id')),
        array('customer_id'))
    ->addIndex($installer->getIdxName('capayable/customer', array('customer_email')),
        array('customer_email'))
    ->setComment('Capayable Customer Data');

$installer->getConnection()->createTable($table);

$installer->endSetup();