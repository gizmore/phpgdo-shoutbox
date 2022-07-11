<?php
namespace GDO\Shoutbox\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Core\GDT_Token;
use GDO\Core\GDT_Object;
use GDO\Shoutbox\GDO_Shoutbox;
use GDO\Form\GDT_Validator;
use GDO\User\GDO_User;
use GDO\Core\Website;
use GDO\Form\GDT_Submit;

/**
 * Delete a shoutbox message via mail.
 * Validates by token and redirects back.
 * @author gizmore
 * @version 6.10.4
 */
final class Delete extends MethodForm
{
    public function isCLI() { return false; }
    
    public function createForm(GDT_Form $form) : void
    {
    	$form->addField(GDT_Object::make('shout')->table(GDO_Shoutbox::table())->notNull());
    	$form->addField(GDT_Validator::make()->validatorFor($form, 'shout', [$this, 'validateDeleted']));
    	$form->addField(GDT_Token::make('token')->initial()->notNull());
    	$form->addField(GDT_Validator::make()->validatorFor($form, 'shout', [$this, 'validateToken']));
        $form->actions()->addField(GDT_Submit::make());
    }

    public function validateDeleted(GDT_Form $form, GDT_Object $field, GDO_Shoutbox $shout=null)
    {
        if ($shout)
        {
            if ($shout->isDeleted())
            {
                return $field->error('err_deleted', [$shout->displayName()]);
            }
        }
        return true;
    }
    
    public function validateToken(GDT_Form $form, GDT_Object $field, GDO_Shoutbox $shout=null)
    {
        if ($shout)
        {
            $user = GDO_User::current();
            if ($user->isStaff())
            {
                return true;
            }
            $hash = $shout->gdoHashcode();
            $token = $form->getFormVar('token');
            if ($hash !== $token)
            {
                return $field->error('err_token');
            }
        }
        return true;
    }
    
    /**
     * @return GDO_Shoutbox
     */
    public function getShout()
    {
        return $this->getForm()->getFormValue('shout');
    }

    public function formValidated(GDT_Form $form)
    {
        $shout = $this->getShout();
        $shout->markDeleted();
        return Website::redirectMessage(
            'msg_shout_deleted', null, Website::hrefBack());
    }

}
