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

JLoader::register('NevigenServerHelperRoute', JPATH_SITE . '/components/com_nevigen_server/helpers/route.php');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

$controller = BaseController::getInstance('NevigenServer');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();