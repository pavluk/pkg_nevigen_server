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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

class NevigenServerModelShortCode extends AdminModel
{

    /**
     * Returns a Table object, always creating it.
     *
     * @param string $type The table type to instantiate
     * @param string $prefix A prefix for the table class name.
     * @param array $config Configuration array for model.
     *
     * @return  Table  A database object.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getTable($type = 'ShortCodes', $prefix = 'NevigenServerTable', $config = array())
    {
        return Table::getInstance($type, $prefix, $config);
    }

    /**
     * Abstract method for getting the form from the model.
     *
     * @param array $data Data for the form.
     * @param boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  Form|boolean  A Form object on success, false on failure.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_nevigen_server.shortcode', 'shortcode', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) return false;

        // Get item id
        $id = (int)$this->getState('shortcode.id', Factory::getApplication()->input->get('id', 0));

        // Modify the form based on Edit State access controls
        if ($id != 0 && !Factory::getUser()->authorise('core.edit.state', 'com_nevigen_server.shortcode.' . $id)) {
            $form->setFieldAttribute('state', 'disabled', 'true');
            $form->setFieldAttribute('state', 'filter', 'unset');
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_nevigen_server.edit.shortcode.data', array());
        if (empty($data)) $data = $this->getItem();
        $this->preprocessData('com_nevigen_server.shortcode', $data);

        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param array $data The form data.
     *
     * @return  boolean  True on success.
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function save($data)
    {
        if (empty($data['order_id'])) return false;

        $data['id'] = 0;
        $data['number'] = $this->generation('number');
        $data['word'] = $this->generation('word');
        $data['mix'] = $this->generation('mix');

        if (parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');

            return $id;
        }

        return false;
    }

    protected function generation($type)
    {
        if ($type === 'number') {
            $table = $this->getTable();
            $min = 1134;
            $max = 999999;
            $value = rand($min,$max);
            while ($table->load(array($type => $value))) $value = rand($min,$max);

            return $value;
        } elseif ($type === 'word') {
            $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
            $length = 5;
            $table = $this->getTable();
            $value = substr(str_shuffle($permitted_chars), 0, $length);
            while ($table->load(array($type => $value))) $value = substr(str_shuffle($permitted_chars), 0, $length);

            return $value;
        } elseif ($type === 'mix') {
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $length = 5;
            $table = $this->getTable();
            $value = substr(str_shuffle($permitted_chars), 0, $length);
            while ($table->load(array($type => $value))) $value = substr(str_shuffle($permitted_chars), 0, $length);

            return $value;
        } else return false;
    }
}