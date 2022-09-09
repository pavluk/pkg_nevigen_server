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
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Registry\Registry;

class  NevigenServerModelUpdate extends BaseDatabaseModel
{
    /**
     * Method to auto-populate the model state.
     *
     * @throws  Exception
     *
     * @since  1.0.0
     */
    protected function populateState()
    {
        $app = Factory::getApplication('site');

        $this->setState('project.element', $app->input->get('element', ''));
        $this->setState('download.key', $app->input->getCmd('download_key', ''));

        // Merge global and menu item params into new object
        $params = $app->getParams();
        $menuParams = new Registry();
        $menu = $app->getMenu()->getActive();
        if ($menu) {
            $menuParams->loadString($menu->getParams());
        }
        $mergedParams = clone $menuParams;
        $mergedParams->merge($params);

        // Set params state
        $this->setState('params', $mergedParams);
    }
    /**
     * Method to get joomla update server xml.
     *
     *
     * @throws  Exception
     *
     * @return  string|Exception  Update servers xml string on success, exception on failure.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getXml($pk = null)
    {
        $updates    = new SimpleXMLElement('<updates/>');
        File::append(JPATH_CACHE.'/com_nevigen_server/plg_system_nevigen_hidden_payship.xml', $updates);

        exit('xxx');
    }
}