<?php
namespace GDO\Shoutbox\Method;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_Method;
use GDO\DB\Query;
use GDO\Shoutbox\GDO_Shoutbox;
use GDO\Shoutbox\Module_Shoutbox;
use GDO\Table\GDT_Table;
use GDO\Table\MethodQueryList;
use GDO\UI\GDT_Link;

final class Box extends MethodQueryList
{

	public function isOrdered(): bool { return false; }

	public function isSearched(): bool { return false; }

	public function getDefaultIPP(): int
	{
		return Module_Shoutbox::instance()->cfgBoxsize();
	}

	public function getDefaultOrder(): ?string
	{
		return 'shout_created DESC';
	}

	public function gdoTable(): GDO
	{
		return GDO_Shoutbox::table();
	}

	public function getQuery(): Query
	{
		return parent::getQuery()->where('shout_deleted IS NULL');
	}

	public function execute(): GDT
	{
		$add = AddSidebar::make();
		$add->getForm()->slim();
		return parent::execute()->addFieldFirst(
			GDT_Link::make('link_shoutbox')->text('gdo_shoutbox')->
			href(href('Shoutbox', 'Table')))->addField(
			GDT_Method::make()->method($add));
	}

	public function setupCollection(GDT_Table $table): void
	{
		parent::setupCollection($table);
		$table->headers->name = 'sht';
	}

}
