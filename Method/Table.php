<?php
namespace GDO\Shoutbox\Method;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_Method;
use GDO\DB\Query;
use GDO\Shoutbox\GDO_Shoutbox;
use GDO\Table\MethodQueryList;


final class Table extends MethodQueryList
{

	public function gdoTable(): GDO
	{
		return GDO_Shoutbox::table();
	}

	public function getDefaultOrder(): ?string { return 'shout_created DESC'; }

//     public function getTitleLangKey()
//     {
//         return 'gdo_shoutbox';
//     }

//     public function getTableTitle()
//     {
//         return t('list_shoutbox_box', [$this->table->countItems()]);
//     }

	public function getQuery(): Query
	{
		return parent::getQuery()->where('shout_deleted IS NULL');
	}

	public function execute(): GDT
	{
		$response = parent::execute();
		return $response->addField(GDT_Method::with(Add::make()));
	}

}
