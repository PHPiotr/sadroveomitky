<?php
defined('_JEXEC') or die;

$oSession = JFactory::getSession();

$sErrorSession = $oSession->get('error');
$sSuccessSession = $oSession->get('success');
$sNameSession = $oSession->get('name');
$sEmailSession = $oSession->get('email');
$sMessageSession = $oSession->get('message');
$sIssetSession = $oSession->get('isset');

$sIsNameSession = $oSession->get('is-name');
$sIsPhoneSession = $oSession->get('is-phone');
$sIsEmailSession = $oSession->get('is-email');
$sIsMessageSession = $oSession->get('is-message');

ModProfishopContactHelper::processSession('error');
ModProfishopContactHelper::processSession('success');
ModProfishopContactHelper::processSession('name');
ModProfishopContactHelper::processSession('email');
ModProfishopContactHelper::processSession('message');

ModProfishopContactHelper::processSession('is-name');
ModProfishopContactHelper::processSession('is-phone');
ModProfishopContactHelper::processSession('is-email');
ModProfishopContactHelper::processSession('is-message');
?>
<h3><?php echo!empty($sSuccessSession) ? $sSuccessSession : (!empty($sErrorSession) ? $sErrorSession : JModuleHelper::getModule('mod_profishop_contact')->title); ?></h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal" method="post">

	<input id="inputSubject" type="text" name="inputSubject" />

	<div class="control-group">
		<label class="control-label" for="inputName"><?php echo $sNameLabel; ?></label>
		<div class="controls">
			<input type="text" id="inputName" name="inputName" maxlength="50"<?php if (!empty($sNameSession)): ?> placeholder="<?php echo $sNameSession; ?>"<?php endif; ?><?php if (!empty($sIsNameSession)): ?> value="<?php echo $sIsNameSession; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="inputPhone"><?php echo $sPhoneLabel; ?></label>
		<div class="controls">
			<input type="text" id="inputPhone" name="inputPhone" maxlength="50"<?php if (!empty($sIsPhoneSession)): ?> value="<?php echo $sIsPhoneSession; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="inputEmail"><?php echo $sEmailLabel; ?></label>
		<div class="controls">
			<input type="text" id="inputEmail" name="inputEmail" maxlength="255"<?php if (!empty($sEmailSession)): ?> placeholder="<?php echo $sEmailSession; ?>"<?php endif; ?><?php if (!empty($sIsEmailSession)): ?> value="<?php echo $sIsEmailSession; ?>"<?php endif; ?>>
		</div>
	</div>

	<label for="inputMessage"><?php echo $sMessageLabel; ?></label>
	<textarea id="inputMessage" name="inputMessage" maxlength="1000"<?php if (!empty($sMessageSession)): ?> placeholder="<?php echo $sMessageSession; ?>"<?php endif; ?>><?php if (!empty($sIsMessageSession)): ?><?php echo $sIsMessageSession; ?><?php endif; ?></textarea>

	<label class="checkbox active" for="copyInput">
		<span class="hide-radio"></span>
		<input id="copyInput" name="copyInput" type="checkbox" value="1" checked /><?php echo $sCopyText; ?>
	</label>
	<button name="submit" type="submit" class="btn submit pull-right"><?php echo $sButtonText; ?> <span class="icon-arrow-right"></span></button>
	<div class="clearfix"></div>

	<?php echo JHtml::_('form.token'); ?>

</form>

