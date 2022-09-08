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

class NevigenServerModelExtension extends AdminModel
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
            $item->file = false;
            if (!empty($item->id)) {
                $params = ComponentHelper::getParams('com_nevigen_server');
                $path = $params->get('files_folder') . '/' . $item->element;
                // Check file
                $item->file = (!empty(Folder::files($path, 'download', false)));
            }
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
    public function getTable($type = 'Extensions', $prefix = 'NevigenServerTable', $config = array())
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
        $form = $this->loadForm('com_nevigen_server.extension', 'extension', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) return false;

        // Get item id
        $id = (int)$this->getState('extension.id', Factory::getApplication()->input->get('id', 0));

        // Modify the form based on Edit State access controls
        if ($id != 0 && !Factory::getUser()->authorise('core.edit.state', 'com_nevigen_server.extension.' . $id)) {
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
        $data = Factory::getApplication()->getUserState('com_nevigen_server.edit.extension.data', array());
        if (empty($data)) $data = $this->getItem();
        $this->preprocessData('com_nevigen_server.extension', $data);

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
        // Check element is already exist
        $element = $data['element'];
        $checkElement = $this->getTable();
        $checkElement->load(array('element' => $element));
        if (!empty($checkElement->id) && ((int)$checkElement->id != $pk || $isNew)) {
            $this->setError(Text::_('COM_NEVIGEN_SERVER_ERROR_ELEMENT_NOT_UNIQUE'));

            return false;
        }

        if ((int)$data['paid'] === 1 && empty($data['support'])) {

            $this->setError(Text::_('COM_NEVIGEN_SERVER_ERROR_ELEMENT_SUPPORT'));


            return false;
        }

        if (parent::save($data)) {
            $id = $this->getState($this->getName() . '.id');
            $params = ComponentHelper::getParams('com_nevigen_server');
            $path = $params->get('files_folder') . '/' . $data['element'];

            if (!Folder::exists($path)) Folder::create($path);

            // Remove old files
            $files = Folder::files($path, 'download', false, true);
            if (!empty($data['file_upload']['tmp_name']) && !empty($files)) {
                foreach ($files as $file) File::delete($file);
            }

            // Upload new file
            $file = $data['file_upload'];
            if (!empty($file['tmp_name'])) {
                $name = 'download.' . File::getExt($file['name']);
                File::upload($file['tmp_name'], $path . '/' . $name, false, true);
            }

            return $id;
        }

        return false;
    }


}