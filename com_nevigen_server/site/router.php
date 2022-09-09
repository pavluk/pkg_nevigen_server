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

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Menu\AbstractMenu;

class NevigenServerRouter extends RouterView
{
    /**
     * Router segments.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $_segments = array();

    /**
     * Router ids.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $_ids = array();

    /**
     * Router constructor.
     *
     * @param   CMSApplication  $app   The application object.
     * @param   AbstractMenu    $menu  The menu object to work with.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function __construct($app = null, $menu = null)
    {
        // Update route
        $update = new RouterViewConfiguration('update');
        $this->registerView($update);

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }
    

    /**
     * Method to get the segment(s) for update.
     *
     * @param   string  $id     ID of the item to retrieve the segments.
     * @param   array   $query  The request that is built right now.
     *
     * @return  array|string  The segments of this item.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getUpdateSegment($id, $query)
    {
        return array(1 => 1);
    }

    /**
     * Method to get the segment(s) for download.
     *
     * @param   string  $id     ID of the item to retrieve the segments.
     * @param   array   $query  The request that is built right now.
     *
     * @return  array|string  The segments of this item.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getDownloadSegment($id, $query)
    {
        return array(1 => 1);
    }


    /**
     * Method to get the id for update.
     *
     * @param   string  $segment  Segment to retrieve the id.
     * @param   array   $query    The request that is parsed right now.
     *
     * @return  integer|false  The id of this item or false.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getUpdateId($segment, $query)
    {
        return 1;
    }
}

/**
 * NevigenServer router functions.
 *
 * @param   array &$query  An array of url arguments.
 *
 * @throws  Exception
 *
 * @return  array  The url arguments to use to assemble the subsequent URL.
 *
 * @since  __DEPLOY_VERSION__
 */
function NevigenServerBuildRoute(&$query)
{
    $app    = Factory::getApplication();
    $router = new NevigenServerRouter($app, $app->getMenu());

    return $router->build($query);
}

/**
 * Parse the segments of a url.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @throws  Exception
 *
 * @return  array  The url attributes to be used by the application.
 *
 * @since  __DEPLOY_VERSION__
 */
function NevigenServerParseRoute($segments)
{
    $app    = Factory::getApplication();
    $router = new NevigenServerRouter($app, $app->getMenu());

    return $router->parse($segments);
}