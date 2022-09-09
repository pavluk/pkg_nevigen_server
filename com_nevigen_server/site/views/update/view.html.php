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
use Joomla\CMS\MVC\View\HtmlView;

class NevigenServerViewUpdate extends HtmlView
{
    /**
     * Update server xml string.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $xml;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function display($tpl = null)
    {
        $this->xml = $this->get('XML');

        // Check for errors
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode('\n', $errors), 500);
        }

        // Set xml response
        $app = Factory::getApplication();
        $app->setHeader('Content-Type', 'application/xml; charset=utf-8', true);

        $app->sendHeaders();

        echo $this->xml;

        $app->close();
    }
}