<?phpuse GDO\Shoutbox\GDO_Shoutbox;use GDO\User\GDO_User;use GDO\UI\GDT_Link;use GDO\UI\GDT_Button;/** @var $shout GDO_Shoutbox **/
?>
<div class="gdo6-shoutbox-message">
  <div>    <b><?=$shout->getCreator()->renderName()?></b>    <span><?=$shout->displayCreated()?></span>  </div>  <p><?=$shout->gdoDisplay('shout_text')?></p><?php if ($shout->canDelete(GDO_User::current())) : ?>  <?=GDT_Button::make()->href($shout->hrefEdit())->icon('edit')->render()?><?php endif; ?></div>