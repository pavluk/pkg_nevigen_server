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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tabstate');
HTMLHelper::_('formbehavior.chosen', 'select');

Factory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function (task) {
		if (task == "order.cancel" || document.formvalidator.isValid(document.getElementById("item-form"))) {
		let form = document.querySelector("#item-form"),
				mSelects = form.querySelectorAll("select[multiple]");
			for (let i = 0; i < mSelects.length; i++) {
				let item = mSelects[i];
				if (item.value === "") {
					let newInput = document.createElement("input");
					newInput.setAttribute("name", item.getAttribute("name").replace("[]", ""));
					newInput.setAttribute("type", "hidden");
					form.append(newInput);
				}
			}
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');

?>
<form action="<?php echo Route::_('index.php?option=com_nevigen_server&view=order&id=' . $this->item->id); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate translate-tabs" enctype="multipart/form-data">
    <div class="form-inline form-inline-header">
        <?php
        echo $this->form->renderField('domain');
        echo $this->form->renderField('extension');
        echo $this->form->renderField('joomshopping');
        echo $this->form->renderField('downloads'); ?>
    </div>
    <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general', 'class')); ?>
    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_NEVIGEN_SERVER_ORDER')); ?>
    <fieldset class="form-horizontal form-horizontal-desktop">
        <?php echo $this->form->renderFieldset('global'); ?>
    </fieldset>
    <?php echo HTMLHelper::_('bootstrap.endTab'); ?>
    <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="return" value="<?php echo Factory::getApplication()->input->getCmd('return'); ?>"/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
