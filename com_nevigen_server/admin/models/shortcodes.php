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

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;

class NevigenServerModelShortCodes extends ListModel
{
    /**
     * Constructor.
     *
     * @param array $config An optional associative array of configuration settings.
     *
     * @since  __DEPLOY_VERSION__
     */
    public function __construct($config = array())
    {
        // Add the ordering filtering fields whitelist
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 's.id',
                'order_id', 's.title',
                'number', 's.number',
                'word', 's.word',
                'mix', 's.mix',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * @param string $ordering An optional ordering field.
     * @param string $direction An optional direction (asc|desc).
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Set search filter state
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information
        $ordering = empty($ordering) ? 's.id' : $ordering;
        $direction = empty($direction) ? 'desc' : $direction;

        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * @param string $id A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getStoreId($id = '')
    {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    /**
     * Build an sql query to load versions list.
     *
     * @return  JDatabaseQuery  Database query to load versions list.
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select(array('s.*'))
            ->from($db->quoteName('#__nevigen_server_order_shortcodes', 's'));

        // Join order
        $query->select(array('o.domain','o.joomshopping'))
            ->leftJoin($db->quoteName('#__nevigen_server_orders', 'o') . ' ON o.id = s.order_id');
        // Filter by search
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('s.id = ' . (int)substr($search, 3));
            } else {
                $sql = array();
                $columns = array('s.number', 's.word', 's.mix');

                foreach ($columns as $column) {
                    $sql[] = $db->quoteName($column) . ' LIKE '
                        . $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                }

                $query->where('(' . implode(' OR ', $sql) . ')');
            }
        }

        // Group by
        $query->group(array('s.id'));

        // Add the list ordering clause
        $ordering = $this->state->get('list.ordering', 's.id');
        $direction = $this->state->get('list.direction', 'desc');

        $query->order($db->escape($ordering) . ' ' . $db->escape($direction));

        return $query;
    }

    /**
     * Method to get an array of invoices data.
     *
     * @return  mixed  Complaints objects array on success, false on failure.
     *
     * @throws Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getItems()
    {
        if ($items = parent::getItems()) {
            foreach ($items as $item) {
                $id = (!empty($item->joomshopping)) ? $item->joomshopping : $item->order_id;
                $item->title = Text::sprintf('COM_NEVIGEN_SERVER_SHORTCODE_TITLE', $id, $item->domain);
            }
        }

        return $items;
    }
}