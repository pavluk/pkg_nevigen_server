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

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

class NevigenServerHelper extends ContentHelper
{
    /**
     * Configure the linkbar.
     *
     * @param   string  $vName  The name of the active view.
     *
     * @since  __DEPLOY_VERSION__
     */
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(Text::_('COM_NEVIGEN_SERVER_EXTENSIONS'),
            'index.php?option=com_nevigen_server&view=extensions',
            $vName == 'extensions');

        JHtmlSidebar::addEntry(Text::_('COM_NEVIGEN_SERVER_ORDERS'),
            'index.php?option=com_nevigen_server&view=orders',
            $vName == 'orders');
        JHtmlSidebar::addEntry(Text::_('COM_NEVIGEN_SERVER_SHORTCODES'),
            'index.php?option=com_nevigen_server&view=shortcodes',
            $vName == 'shortcodes');

    }
}