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

class NevigenServerControllerExtension extends FormController
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
    protected $text_prefix = 'COM_NEVIGEN_SERVER_EXTENSION';

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key.
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function save($key = null, $urlVar = null)
    {
        // Check for request forgeries
        $this->checkToken();

        // Set file to data
        $data  = $this->input->post->get('jform', array(), 'array');
        $files = $this->input->files->get('jform', '', 'raw');

        $data['file_upload'] = (!empty($files['file_upload'])) ? $files['file_upload'] : '';

        $this->input->post->set('jform', $data);

        return parent::save($key, $urlVar);
    }
}