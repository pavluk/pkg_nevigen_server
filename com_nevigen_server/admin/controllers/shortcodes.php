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

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class NevigenServerControllerShortCodes extends AdminController
{
    /**
     * The URL option for the component.
     *
     * @var    string
     * @since  __DEPLOY_VERSION__
     */
    protected $option = 'com_nevigen_server';

    /**
     * The prefix to use with controller messages.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $text_prefix = 'COM_NEVIGEN_SERVER_SHORTCODES';

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name.
     * @param   string  $prefix  The class prefix.
     * @param   array   $config  The array of possible config values.
     *
     * @return  BaseDatabaseModel|\NevigenServerModelShortCodes  A model object.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getModel($name = 'ShortCodes', $prefix = 'NevigenServerModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}