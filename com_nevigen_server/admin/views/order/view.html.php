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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class NevigenServerViewOrder extends HtmlView
{
    /**
     * Model state variables.
     *
     * @var  Joomla\CMS\Object\CMSObject
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $state;

    /**
     * Form object.
     *
     * @var  Form
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $form;

    /**
     * Project object.
     *
     * @var  object
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $item;

    /**
     * Execute and display a template script.
     *
     * @param string $tpl The name of the template file to parse.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Check for errors
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
        }

        // Add title and toolbar
        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add title and toolbar.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function addToolbar()
    {
        $isNew = ($this->item->id == 0);
        $canDo = NevigenServerHelper::getActions('com_nevigen_server', 'order', $this->item->id);
        $app = Factory::getApplication();

        // Disable menu
        $app->input->set('hidemainmenu', true);

        // Set page title
        $title = ($isNew) ? Text::_('COM_NEVIGEN_SERVER_ORDER_ADD') : Text::_('COM_NEVIGEN_SERVER_ORDER_EDIT');
        ToolbarHelper::title(Text::_('COM_NEVIGEN_SERVER') . ': ' . $title, 'cube');

        // Add apply & save buttons
        if ($canDo->get('core.edit')) {
            ToolbarHelper::apply('order.apply');
            ToolbarHelper::save('order.save');
        }
        
        // Add cancel button
        ToolbarHelper::cancel('order.cancel', 'JTOOLBAR_CLOSE');


    }
}