<?php
namespace GDO\Shoutbox;

use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Core\GDT_DeletedAt;
use GDO\Core\GDT_DeletedBy;
use GDO\Core\GDT_EditedAt;
use GDO\Core\GDT_EditedBy;
use GDO\Core\GDT_String;
use GDO\Core\GDT_Template;
use GDO\Date\Time;
use GDO\User\GDO_User;

/**
 * Shoutbox table/entity.
 *
 * @author gizmore
 */
final class GDO_Shoutbox extends GDO
{

	public static function lastShoutFrom(GDO_User $user)
	{
		return self::table()->select()->
		where("shout_creator={$user->getID()}")->
		order('shout_created DESC')->
		first()->exec()->fetchObject();
	}

	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('shout_id'),
			GDT_String::make('shout_text')->min(3)->max(256)->notNull(),
			GDT_CreatedAt::make('shout_created'),
			GDT_CreatedBy::make('shout_creator'),
			GDT_EditedAt::make('shout_edited'),
			GDT_EditedBy::make('shout_editor'),
			GDT_DeletedAt::make('shout_deleted'),
			GDT_DeletedBy::make('shout_deletor'),
		];
	}

	public function renderList(): string
	{
		return GDT_Template::php('Shoutbox', 'shout_li.php', ['shout' => $this]);
	}

	public function getAge() { return Time::getAgo($this->getCreated()); }

	public function getCreated() { return $this->gdoVar('shout_created'); }

	public function displayCreated() { return Time::displayDate($this->getCreated()); }

	###################
	### Permissions ###
	###################

	public function getCreator() { return $this->gdoValue('shout_creator'); }
	##############
	### Static ###
	##############

	public function hrefEdit() { return href('Shoutbox', 'Edit', "&id={$this->getID()}"); }

	##############
	### Render ###
	##############

	public function canDelete(GDO_User $user)
	{
		if ($user->isStaff())
		{
			return true;
		}
		return false;
	}

}
