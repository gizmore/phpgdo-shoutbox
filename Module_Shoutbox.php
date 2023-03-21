<?php
namespace GDO\Shoutbox;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_Method;
use GDO\Core\GDT_UInt;
use GDO\Date\GDT_Duration;
use GDO\Shoutbox\Method\Box;
use GDO\UI\GDT_Link;
use GDO\UI\GDT_Page;
use GDO\User\GDO_User;

/**
 * Shoutbox module.
 *
 * @version 6.10.4
 * @since 6.10.4
 * @author gizmore
 */
final class Module_Shoutbox extends GDO_Module
{

	public function onLoadLanguage(): void
	{
		$this->loadLanguage('lang/shoutbox');
	}

	public function getClasses(): array
	{
		return [
			GDO_Shoutbox::class,
		];
	}

	public function getConfig(): array
	{
		return [
			GDT_Checkbox::make('shout_mails')->initial('1'),
			GDT_Checkbox::make('shout_guests')->initial('1'),
			GDT_Checkbox::make('shout_sidebar')->initial('1'),
			GDT_Checkbox::make('shout_sidebar_box')->initial('1'),
			GDT_Checkbox::make('shout_captcha_guests')->initial('1'),
			GDT_Checkbox::make('shout_captcha_member')->initial('0'),
			GDT_UInt::make('shout_boxsize')->initial('3'),
			GDT_Duration::make('shout_cooldown_guests')->initial('1m'),
			GDT_Duration::make('shout_cooldown_member')->initial('10s'),
		];
	}

	public function onInitSidebar(): void
	{
		if ($this->cfgSidebar())
		{
			if ($this->cfgSidebarBox())
			{
				GDT_Page::instance()->leftBar()->addField(
					GDT_Method::make()->method(Box::make()));
			}
			else
			{
				GDT_Page::instance()->leftBar()->addField(
					GDT_Link::make()->text('gdo_sidebar')->href(
						href('Shoutbox', 'Table')));
			}
		}
	}

	public function cfgSidebar() { return $this->getConfigVar('shout_sidebar'); }

	public function cfgSidebarBox() { return $this->getConfigVar('shout_sidebar_box'); }

	public function onIncludeScripts(): void
	{
		$this->addCSS('css/gdo6-shoutbox.css');
		$this->addJS('js/gdo6-shoutbox.js');
	}

	public function cfgMail() { return $this->getConfigVar('shout_mails'); }

	public function cfgGuests() { return $this->getConfigVar('shout_guests'); }

	public function cfgBoxsize() { return $this->getConfigVar('shout_boxsize'); }

	public function cfgCooldown(GDO_User $user)
	{
		return $user->isMember() ?
			$this->cfgCooldownMember() :
			$this->cfgCooldownGuests();
	}

	public function cfgCooldownMember() { return $this->getConfigValue('shout_cooldown_member'); }

	public function cfgCooldownGuests() { return $this->getConfigValue('shout_cooldown_guests'); }

	public function cfgCaptcha(GDO_User $user)
	{
		return $user->isMember() ?
			$this->cfgCaptchaMember() :
			$this->cfgCaptchaGuests();
	}

	public function cfgCaptchaMember() { return $this->getConfigVar('shout_captcha_member'); }

	public function cfgCaptchaGuests() { return $this->getConfigVar('shout_captcha_guests'); }

}
