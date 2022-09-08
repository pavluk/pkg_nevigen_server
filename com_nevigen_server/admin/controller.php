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

use Joomla\CMS\MVC\Controller\BaseController;

class NevigenServerController extends BaseController
{
    /**
     * The default view.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $default_view = 'extensions';
}