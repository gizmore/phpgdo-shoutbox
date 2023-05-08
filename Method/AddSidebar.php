<?php
declare(strict_types=1);
namespace GDO\Shoutbox\Method;

use GDO\Form\GDT_Form;

/**
 * Add a shoutbox entry via sidebar.
 *
 *  - Checks for cooldown.
 *  - Sends moderation mail.
 *
 * @version 7.0.3
 * @since 6.10.4
 * @author gizmore
 */
final class AddSidebar extends Add
{

	public function getFormName(): string
	{
		return 'add_shout_sidebar';
	}

	protected function createForm(GDT_Form $form): void
	{
		parent::createForm($form);
		$form->slim();
		$form->noFocus();
		$form->titleNone();
		$form->action(href('Shoutbox', 'Add'));
	}

	public function getMethodTitle(): string
	{
		return t('mt_shoutbox_add');
	}

}
