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
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;

class NevigenServerModelOrder extends AdminModel
{
    /**
     * Method to get version data.
     *
     * @param integer $pk The id of the version.
     *
     * @return  mixed  Version object on success, false on failure.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {

        }

        return $item;
    }

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
    public function getTable($type = 'Orders', $prefix = 'NevigenServerTable', $config = array())
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
        $form = $this->loadForm('com_nevigen_server.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) return false;

        // Get item id
        $id = (int)$this->getState('order.id', Factory::getApplication()->input->get('id', 0));

        // Modify the form based on Edit State access controls
        if ($id != 0 && !Factory::getUser()->authorise('core.edit.state', 'com_nevigen_server.order.' . $id)) {
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
        $data = Factory::getApplication()->getUserState('com_nevigen_server.edit.order.data', array());
        if (empty($data)) $data = $this->getItem();
        $this->preprocessData('com_nevigen_server.order', $data);

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
        $pk = (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName() . '.id');
        $table = $this->getTable();
        $isNew = true;

        // Load the row if saving an existing item
        if ($pk > 0) {
            $table->load($pk);
            $isNew = false;
        }
        if (isset($data['created']) && $data['created'] === '') {
            $data['created'] = Factory::getDate()->toSql();
        }
        if (isset($data['shutdown']) && $data['shutdown'] === '') {
            $extension = $this->getExtension($data['extension']);
            if (empty($extension)) {
                $this->setError(Text::_('COM_NEVIGEN_SERVER_ERROR_EXTENSION_NOT_FOUND'));

                return false;
            }
            if ($extension->paid) {
                $shutdown = new Date('now +' . $extension->support . ' day');
                $data['shutdown'] = $shutdown->toSql();
            }

        }

        if (parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');
            if ($isNew) {
                /* @var \NevigenServerModelExtension $model */
                $model = BaseDatabaseModel::getInstance('ShortCode', 'NevigenServerModel', array('ignore_request' => true));
                $model->save(array('order_id' => $id));
            }
            return $id;
        }
        return false;
    }

    protected function getExtension($pk)
    {
        if (empty($pk)) return false;

        /* @var \NevigenServerModelExtension $model */
        $model = BaseDatabaseModel::getInstance('Extension', 'NevigenServerModel', array('ignore_request' => true));
        $model->setState('filter.published', 1);

        return $model->getItem($pk);

    }

}