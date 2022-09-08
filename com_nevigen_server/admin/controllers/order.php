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

use Joomla\CMS\MVC\Controller\FormController;

class NevigenServerControllerOrder extends FormController
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
    protected $text_prefix = 'COM_NEVIGEN_SERVER_ORDER';
}