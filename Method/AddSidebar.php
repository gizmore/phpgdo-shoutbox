<?php
namespace GDO\Shoutbox\Method;

use GDO\Form\GDT_Form;

/**
 * Add a shoutbox entry via sidebar.
 * 
 *  - Checks for cooldown.
 *  - Sends moderation mail.
 *  
 * @author gizmore
 * @version 6.10.6
 * @since 6.10.4
 */
final class AddSidebar extends Add
{
    public function formName() { return 'frm_shout'; }
    public function getTitleLangKey() { return 'mt_shoutbox_add'; }
    public function createForm(GDT_Form $form) : void
    {
        parent::createForm($form);
        $form->slim();
        $form->noFocus();
        $form->noTitle();
        $form->action(href('Shoutbox', 'Add'));
    }

}
