<?php
declare(strict_types=1);
namespace GDO\Shoutbox\Method;

use GDO\Captcha\GDT_Captcha;
use GDO\Core\GDT;
use GDO\Core\GDT_String;
use GDO\Date\Time;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_Validator;
use GDO\Form\MethodForm;
use GDO\Mail\Mail;
use GDO\Shoutbox\GDO_Shoutbox;
use GDO\Shoutbox\Module_Shoutbox;
use GDO\User\GDO_User;

/**
 * Add a shoutbox entry.
 *
 *  - Checks for cooldown.
 *  - Sends moderation mail.
 *
 * @version 7.0.3
 * @author gizmore
 */
class Add extends MethodForm
{
    public function isUserRequired(): bool
    {
        return false;
    }

    public function isGuestAllowed(): bool
    {
        return true;
    }


	public function isSavingLastUrl(): bool { return false; }


	protected function createForm(GDT_Form $form): void
	{
		$table = GDO_Shoutbox::table();
		$form->addFields(
			$table->gdoColumn('shout_text'),
		);
		$form->addFields(
			GDT_Validator::make('cooldown')->validatorFor($form, 'shout_text', [$this, 'validateCooldown']),
		);
		if (Module_Shoutbox::instance()->cfgCaptcha(GDO_User::current()))
		{
			$form->addField(GDT_Captcha::make(), $form->getField('cooldown'));
		}
		$form->addField(GDT_AntiCSRF::make());
		$form->actions()->addField(GDT_Submit::make());
	}

	public function formValidated(GDT_Form $form): GDT
	{
		$user = GDO_User::current()->persistent();
		$shout = GDO_Shoutbox::blank([
			'shout_text' => $form->getFormVar('shout_text'),
		])->insert();

		$module = Module_Shoutbox::instance();
		if ($module->cfgMail())
		{
			$this->sendMails($user, $shout);
		}

		return $this->redirectMessage('msg_shouted');
	}

	public function sendMails(GDO_User $user, GDO_Shoutbox $shout)
	{
		foreach (GDO_User::staff() as $staff)
		{
			$this->sendMail($staff, $user, $shout);
		}
	}

	public function sendMail(GDO_User $staff, GDO_User $user, GDO_Shoutbox $shout)
	{
		$mail = Mail::botMail();
		$mail->setSubject(tusr($staff, 'mail_subj_shoutbox', [sitename()]));
		$mail->setBody(tusr($staff, 'mail_body_shoutbox', [
			$staff->renderName(),
			sitename(),
			$user->renderUserName(),
			$shout->gdoDisplay('shout_text'),
			url('Shoutbox', 'Delete', "&form[submit]=1&form[shout]={$shout->getID()}&form[token]={$shout->gdoHashcode()}"),
		]));
		$mail->sendToUser($staff);
	}

	public function getFormName(): string { return 'form_shout'; }

	public function validateCooldown(GDT_Form $form, GDT_String $field, $value)
	{
		if ($shout = GDO_Shoutbox::lastShoutFrom(GDO_User::current()))
		{
			$age = $shout->getAge();
			$cooldown = Module_Shoutbox::instance()->cfgCooldown(GDO_User::current());
			$wait = $cooldown - $age;
			if ($wait > 0)
			{
				return $field->error('err_shout_cooldown', [Time::humanDuration($wait)]);
			}
		}
		return true;
	}

}
