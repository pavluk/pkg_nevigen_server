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

use Joomla\CMS\MVC\Model\ListModel;

class NevigenServerModelExtensions extends ListModel
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
                'id', 'e.id',
                'title', 'e.title',
                'published', 'state', 'e.state',
                'element', 'e.element',
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

        // Set published filter state
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        // List state information
        $ordering = empty($ordering) ? 'e.id' : $ordering;
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
        $id .= ':' . $this->getState('filter.published');

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
            ->select(array('e.*'))
            ->from($db->quoteName('#__nevigen_server_extensions', 'e'));


        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('e.state = ' . (int)$published);
        } elseif ($published === '') {
            $query->where('(e.state = 0 OR e.state = 1)');
        }

        // Filter by search
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('e.id = ' . (int)substr($search, 3));
            } else {
                $sql = array();
                $columns = array('e.id', 'e.element', 'e.title');

                foreach ($columns as $column) {
                    $sql[] = $db->quoteName($column) . ' LIKE '
                        . $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                }

                $query->where('(' . implode(' OR ', $sql) . ')');
            }
        }

        // Group by
        $query->group(array('e.id'));

        // Add the list ordering clause
        $ordering = $this->state->get('list.ordering', 'e.id');
        $direction = $this->state->get('list.direction', 'desc');

        $query->order($db->escape($ordering) . ' ' . $db->escape($direction));

        return $query;
    }
}