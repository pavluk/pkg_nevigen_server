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
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;

FormHelper::loadFieldClass('list');

class JFormFieldExtensions extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var  string
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $type = 'extensions';

    /**
     * Field options array.
     *
     * @var  array
     *
     * @since  __DEPLOY_VERSION__
     */
    protected $_options = null;

    /**
     * Method to get the field options.
     *
     * @return array
     *
     * @throws  Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getOptions()
    {
        if ($this->_options === null) {
            $options = parent::getOptions();
            $db = Factory::getDbo();
            try {
                // Construct the query
                $query = $db->getQuery(true)
                    ->select(array('e.id', 'e.title'))
                    ->from($db->quoteName('#__nevigen_server_extensions', 'e'))
                    ->where('e.state = 1')
                    ->where('e.paid = 1');

                // Return the result
                if (!empty($items = $db->setQuery($query)->loadObjectList())) {
                    //Add empty
                    $empty = new stdClass();
                    $empty->value = '';
                    $empty->text = Text::_('COM_NEVIGEN_SERVER_EXTENSION_SELECT');
                    $options[] = $empty;

                    foreach ($items as $item) {
                        $option = new stdClass();
                        $option->value = $item->id;
                        $option->text = $item->title;

                        $options[] = $option;
                    }

                    $this->_options = $options;
                }
            } catch (Exception $e) {

                throw new Exception(Text::_($e->getMessage()), 404);
            }
        }

        return $this->_options;
    }
}