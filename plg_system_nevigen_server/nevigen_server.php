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

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemNevigen_server extends CMSPlugin
{
    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject,$config);

        if (Factory::getApplication()->isClient('administrator')) {
            // Load language
            $language = Factory::getLanguage();
            $language->load('com_nevigen_server', JPATH_ADMINISTRATOR, $language->getTag(), true);
        }
    }

    public function onDisplayProductEditTabsTab(&$row, &$lists, &$tax_value)
    {
        echo '<li><a href="#com_nevigen_server" data-toggle="tab">'. Text::_('COM_NEVIGEN_SERVER').'</a></li>';
    }

    public function onDisplayProductEditTabs(&$pane, &$row, &$lists, &$tax_value, &$currency){
        HTMLHelper::_('formbehavior.chosen', 'select');

        // Get Form
        $formName = 'com_nevigen_server';
        $form = new Form($formName, array('control' => ''));
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><form/>');
        $form::addFieldPath(JPATH_ADMINISTRATOR.'/components/com_nevigen_server/models/fields');


        $field = $xml->addChild('field');
        $field->addAttribute('label', 'COM_NEVIGEN_SERVER_EXTENSION');
        $field->addAttribute('type', 'extensions');
        $field->addAttribute('name', 'extension_id');

        $form->load($xml);
        if (!empty($row->extension_id)) {
            $form->setValue('extension_id', '', $row->extension_id);
        }
        echo '<div id="com_nevigen_server" class="tab-pane">'.$form->renderField('extension_id').'</div>';
    }

}