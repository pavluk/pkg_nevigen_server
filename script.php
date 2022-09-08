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
use Joomla\CMS\Installer\Adapter\PackageAdapter;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;

class pkg_nevigen_serverInstallerScript
{
    /**
     * Minimum PHP version required to install the extension.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $minimumPhp = '7.4';

    /**
     * Minimum Joomla version required to install the extension.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $minimumJoomla = '3.9.0';

    /**
     * Runs right before any installation action.
     *
     * @param   string                           $type    Type of PostFlight action.
     * @param   InstallerAdapter|PackageAdapter  $parent  Parent object calling object.
     *
     * @throws  Exception
     *
     * @return  boolean True on success, false on failure.
     *
     * @since  __DEPLOY_VERSION__
     */
    function preflight($type, $parent)
    {
        // Check compatible
        if (!$this->checkCompatible('PKG_NEVIGEN_SERVER_')) return false;

        return true;
    }
    /**
     * Method to check compatible.
     *
     * @param   string  $prefix  Language constants prefix.
     *
     * @throws  Exception
     *
     * @return  boolean True on success, false on failure.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function checkCompatible($prefix = null)
    {
        // Check old Joomla
        if (!class_exists('Joomla\CMS\Version'))
        {
            JFactory::getApplication()->enqueueMessage(JText::sprintf($prefix . 'ERROR_COMPATIBLE_JOOMLA',
                $this->minimumJoomla), 'error');

            return false;
        }

        $app = Factory::getApplication();

        // Check PHP
        if (!(version_compare(PHP_VERSION, $this->minimumPhp) >= 0))
        {
            $app->enqueueMessage(Text::sprintf($prefix . 'ERROR_COMPATIBLE_PHP', $this->minimumPhp),
                'error');

            return false;
        }

        // Check joomla version
        if (!(new Version())->isCompatible($this->minimumJoomla))
        {
            $app->enqueueMessage(Text::sprintf($prefix . 'ERROR_COMPATIBLE_JOOMLA', $this->minimumJoomla),
                'error');

            return false;
        }

        return true;
    }
}
