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

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class NevigenServerViewShortcodes extends HtmlView
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
     * Categories array.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $items;

    /**
     * Pagination object.
     *
     * @var  Pagination
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $pagination;

    /**
     * Form object for search filters.
     *
     * @var  Form
     *
     * @since  __DEPLOY_VERSION__
     */
    public $filterForm;

    /**
     * The active search filters.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    public $activeFilters;

    /**
     * View sidebar.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    public $sidebar;

    /**
     * Display the view.
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
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Add title and toolbar
        $this->addToolbar();

        // Prepare sidebar
        NevigenServerHelper::addSubmenu('shortcodes');
        $this->sidebar = JHtmlSidebar::render();

        // Check for errors
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
        }


        return parent::display($tpl);
    }

    /**
     * Add title and toolbar.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function addToolbar()
    {
        $canDo = NevigenServerHelper::getActions('com_nevigen_server', 'shortcodes');
        $toolbar = Toolbar::getInstance();


        // Set page title
        ToolbarHelper::title(Text::_('COM_NEVIGEN_SERVER') . ': ' . Text::_('COM_NEVIGEN_SERVER_SHORTCODES'), 'cube');

        if ($canDo->get('core.edit.state')) {
            ToolbarHelper::trash('shortcodes.trash');
        }


        // Add preferences button
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            ToolbarHelper::preferences('com_nevigen_server');
        }
    }

    /**
     * Returns an array of fields the table can be sorted by.
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getSortFields()
    {
        return [
            's.order_id' => Text::_('COM_NEVIGEN_SERVER_ORDER'),
            's.id' => Text::_('JGRID_HEADING_ID'),
            's.number' => Text::_('COM_NEVIGEN_SERVER_SHORTCODE_NUMBER'),
            's.word' => Text::_('COM_NEVIGEN_SERVER_SHORTCODE_WORD'),
            's.mix' => Text::_('COM_NEVIGEN_SERVER_SHORTCODE_MIX'),
        ];
    }
}

