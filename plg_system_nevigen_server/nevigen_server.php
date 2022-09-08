<?php
/*
 * @package    Nevigen server package
 * @version    __DEPLOY_VERSION__
 * @author     Artem Pavluk - https://nevigen.com
 * @copyright  Copyright © Nevigen.com. All rights reserved.
 * @license    Proprietary. Copyrighted Commercial Software
 * @link       https://nevigen.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Table;

class plgSystemNevigen_server extends CMSPlugin
{
    /**
     * Loads the application object.
     *
     * @var  CMSApplication
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $app = null;

    /**
     * Loads the database object.
     *
     * @var  JDatabaseDriver
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $db = null;

    /**
     * Component params
     *
     * @var  \Joomla\Registry\Registry
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $componentParams = null;

    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        if ($this->app->isClient('administrator')) {
            // Load language
            $language = Factory::getLanguage();
            $language->load('com_nevigen_server', JPATH_ADMINISTRATOR, $language->getTag(), true);
        }

        if ($this->componentParams === null) {
            $this->componentParams = \Joomla\CMS\Component\ComponentHelper::getParams('com_nevigen_server');
        }


    }

    public function onAfterChangeOrderStatus(&$order_id, &$status, &$sendmessage, &$comments)
    {
        $this->createOrderServer($order_id, $status);
    }

    public function onAfterChangeOrderStatusAdmin(&$order_id, &$status, &$sendmessage, &$comments)
    {
        $this->createOrderServer($order_id, $status);
    }

    public function onDisplayProductEditTabsTab(&$row, &$lists, &$tax_value)
    {
        echo '<li><a href="#com_nevigen_server" data-toggle="tab">' . Text::_('COM_NEVIGEN_SERVER') . '</a></li>';
    }

    public function onDisplayProductEditTabs(&$pane, &$row, &$lists, &$tax_value, &$currency)
    {
        HTMLHelper::_('formbehavior.chosen', 'select');

        // Get Form
        $formName = 'com_nevigen_server';
        $form = new Form($formName, array('control' => ''));
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><form/>');
        $form::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_nevigen_server/models/fields');


        $field = $xml->addChild('field');
        $field->addAttribute('label', 'COM_NEVIGEN_SERVER_EXTENSION');
        $field->addAttribute('type', 'extensions');
        $field->addAttribute('name', 'extension_id');

        $form->load($xml);
        if (!empty($row->extension_id)) {
            $form->setValue('extension_id', '', $row->extension_id);
        }
        echo '<div id="com_nevigen_server" class="tab-pane">' . $form->renderField('extension_id') . '</div>';
    }

    protected function createOrderServer($order_id, $status)
    {
        if (empty($order_id) || empty($status)) return;
        if ((int)$this->componentParams->get('payment_status', 6) === (int)$status) {
            $order = JSFactory::getTable('order');
            $order->load($order_id);
            $products = $order->getAllItems();
            if (!empty($products)) {
                $ids = array();
                $domains = array();
                foreach ($products as $product) {
                    $ids[] = $product->product_id;
                    $domain = trim(substr($product->product_freeattributes,
                        strpos($product->product_freeattributes, ':') + 1));
                    if (!empty($domain)) {
                        $domains[$product->product_id] = str_replace(array('www.', 'https://', 'http://', '/'),
                            array('', '', '', ''), $domain);
                    }
                }
                if (!empty($ids)) {
                    $query = $this->db->getQuery(true)
                        ->select(array('product_id', 'extension_id'))
                        ->from('#__jshopping_products')
                        ->where('product_id IN (' . implode(',', $ids) . ')');

                    $extensions = $this->db->setQuery($query)->loadAssocList('product_id');
                    Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nevigen_server/tables');
                    BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nevigen_server/models');
                    foreach ($extensions as $product => $extension) {
                        if (isset($domains[$product])) {
                            /* @var \NevigenServerModelOrder $model */
                            $model = BaseDatabaseModel::getInstance('Order', 'NevigenServerModel',
                                array('ignore_request' => true));
                            $data = array(
                                'id' => 0,
                                'domain' => $domains[$product],
                                'joomshopping' => $order_id,
                                'created_by' => $order->user_id,
                                'state' => 1,
                                'extension' => $extension['extension_id'],
                                'created' => '',
                                'shutdown' => '',
                            );

                            $model->save($data);
                        }
                    }
                }
            }
        }
    }
}