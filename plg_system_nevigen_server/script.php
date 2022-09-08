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
use Joomla\CMS\Installer\InstallerAdapter;

class PlgSystemNevigen_serverInstallerScript
{
    /**
     * Runs right after any installation action.
     *
     * @param string $type Type of PostFlight action. Possible values are:
     * @param InstallerAdapter $parent Parent object calling object.
     *
     * @return  boolean True on success, false on failure.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    function postflight($type, $parent)
    {
        // Enable plugin
        if ($type == 'install') $this->enablePlugin($parent);

        // Add column product
        $this->JoomShoppingAddColumn('#__jshopping_products','extension_id','int(11)');

        return true;
    }
    /**
     * Enable plugin after installation.
     *
     * @param   InstallerAdapter  $parent  Parent object calling object.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function enablePlugin($parent)
    {
        // Prepare plugin object
        $plugin          = new stdClass();
        $plugin->type    = 'plugin';
        $plugin->element = $parent->getElement();
        $plugin->folder  = (string) $parent->getParent()->manifest->attributes()['group'];
        $plugin->enabled = 1;

        // Update record
        Factory::getDbo()->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));
    }

    /**
     * Method to JoomShopping add column constructor
     *
     * @param   string  $column  Column name.
     * @param   string  $type    Column type.
     *
     * @return bool
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function JoomShoppingAddColumn($table = '',$column = null, $type = 'varchar (400)')
    {
        if (empty($table) || empty($column)) return false;

        $db = Factory::getDbo();

        $query = 'SHOW COLUMNS FROM ' . $db->quoteName($table) . 'LIKE ' . $db->quote($column);
        $db->setQuery($query);
        if (empty($db->loadResult()))
        {
            $query = 'alter table ' . $db->quoteName($table) . ' add ' . $column . ' ' . $type;

            $db->setQuery($query);
            $db->execute();
        }

        return true;
    }
}