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

use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

if (!Factory::getUser()->authorise('core.manage', 'com_nevigen_server'))
{
    throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}


JLoader::register('NevigenServerHelper', __DIR__ . '/helpers/nevigen_server.php');

$controller = BaseController::getInstance('NevigenServer');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();