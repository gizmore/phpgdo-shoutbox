<?php
namespace GDO\Shoutbox\Method;

use GDO\Core\GDO;
use GDO\Form\MethodCrud;
use GDO\Shoutbox\GDO_Shoutbox;

/**
 * Edit or delete a Shout.
 *
 * @author gizmore
 */
final class Edit extends MethodCrud
{

	public function hrefList(): string
	{
		return href('Shoutbox', 'Table');
	}

	public function gdoTable(): GDO
	{
		return GDO_Shoutbox::table();
	}

}
