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
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

class com_nevigen_serverInstallerScript
{
    /**
     * Extension params for check.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $setParams = array(
        'sef_advanced' => 1,
        'files_folder' => true,
        'payment_status' => 6,
    );

    /**
     * Runs right after any installation action.
     *
     * @param string $type Type of PostFlight action.
     * @param InstallerAdapter $parent Parent object calling object.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    function postflight($type, $parent)
    {
        // Check databases
        $this->checkTables($parent);

        // Check extension params
        $this->checkExtensionParams($parent);
    }

    /**
     * Method to create database tables in not exist.
     *
     * @param InstallerAdapter $parent Parent object calling object.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function checkTables($parent)
    {
        if ($sql = file_get_contents($parent->getParent()->getPath('extension_administrator')
            . '/sql/install.mysql.utf8.sql')) {
            $db = Factory::getDbo();

            foreach ($db->splitSql($sql) as $query) {
                $db->setQuery($db->convertUtf8mb4QueryToUtf8($query));
                try {
                    $db->execute();
                } catch (JDataBaseExceptionExecuting $e) {
                    Log::add(Text::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $e->getMessage()), Log::WARNING, 'jerror');
                }
            }
        }
    }

    /**
     * Method to check extension params and set if need.
     *
     * @param InstallerAdapter $parent Parent object calling object.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function checkExtensionParams($parent)
    {
        if (!empty($this->setParams)) {
            $element = $parent->getElement();
            $folder = (string)$parent->getParent()->manifest->attributes()['group'];

            // Get extension
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->select(array('extension_id', 'params'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('element') . ' = ' . $db->quote($element));
            if (!empty($folder)) {
                $query->where($db->quoteName('folder') . ' = ' . $db->quote($folder));
            }
            if ($extension = $db->setQuery($query)->loadObject()) {
                $extension->params = new Registry($extension->params);

                // Check params
                $uri = Uri::getInstance();
                $needUpdate = false;

                foreach ($this->setParams as $path => $value) {
                    if (!$extension->params->exists($path)) {
                        if ($path === 'files_folder' && $value === true) {
                            $pathFolder = str_replace($uri->getHost(), '', JPATH_ROOT);
                            $value = Path::clean($pathFolder . '/' . 'NevigenServerExtensions');
                        }

                        $needUpdate = true;
                        $extension->params->set($path, $value);
                    }
                }

                // Update
                if ($needUpdate) {
                    $extension->params = (string)$extension->params;
                    $db->updateObject('#__extensions', $extension, 'extension_id');
                }
            }
        }
    }


}