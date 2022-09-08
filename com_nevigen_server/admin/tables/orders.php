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

use Joomla\CMS\Table\Table;

class NevigenServerTableOrders extends Table
{
    /**
     * Constructor.
     *
     * @param   JDatabaseDriver &$db  Database connector object
     *
     * @since  __DEPLOY_VERSION__
     */
    function __construct(&$db)
    {
        parent::__construct('#__nevigen_server_orders', 'id', $db);

        // Set the alias since the column is called state
        $this->setColumnAlias('published', 'state');
    }
}