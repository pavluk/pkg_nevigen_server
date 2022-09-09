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

use Joomla\CMS\Helper\RouteHelper;

class NevigenServerHelperRoute extends RouteHelper
{
    /**
     * Fetches jupdate route.
     *
     * @param string $element The element of the project.
     * @param string $download_key The download key value.
     *
     * @return  string  Joomla update server view link.
     *
     * @since  __DEPLOY_VERSION__
     */
    public static function getUpdateRoute($element = null, $download_key = null)
    {
        $link = 'index.php?option=com_nevigen_server&view=update';

        if (!empty($element)) {
            $link .= '&element=' . $element;
        }

        if (!empty($download_key)) {
            $link .= '&download_key=' . $download_key;
        }

        return $link;
    }
}