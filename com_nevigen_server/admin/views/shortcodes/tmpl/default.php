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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user      = Factory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$columns = 6;
?>
<form action="<?php echo Route::_('index.php?option=com_nevigen_server&view=orders'); ?>" method="post"
      name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table id="ordersList" class="table table-striped">
                <thead>
                <tr>
                    <th width="1%" class="center">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>
                    <th class="nowrap">
                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_NEVIGEN_SERVER_ORDER', 's.order_id',
                            $listDirn, $listOrder); ?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo Text::_('COM_NEVIGEN_SERVER_SHORTCODE_NUMBER');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo Text::_('COM_NEVIGEN_SERVER_SHORTCODE_WORD');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo Text::_('COM_NEVIGEN_SERVER_SHORTCODE_MIX');?>
                    </th>
                    <th width="1%" class="nowrap hidden-phone center">
                        <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 's.id', $listDirn, $listOrder); ?>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="<?php echo $columns; ?>">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $canEdit = $user->authorise('core.edit', 'com_nevigen_server.order.' . $item->id);
                    $canChange = $user->authorise('core.edit.state', 'com_nevigen_server.order.' . $item->id);
                    ?>
                    <tr class="row<?php echo $i % 2; ?>" item-id="<?php echo $item->id ?>">
                        <td class="center">
                            <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                        </td>

                        <td class="nowrap">
                                <?php echo $item->title; ?>
                        </td>
                        <td class="hidden-phone">
                            <?php echo $this->escape($item->number); ?>
                        </td>
                        <td class="hidden-phone">
                            <?php echo $this->escape($item->word); ?>
                        </td>
                        <td class="hidden-phone">
                            <?php echo $this->escape($item->mix); ?>
                        </td>
                        <td class="hidden-phone center">
                            <?php echo $item->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
