<?php
/*
 * @version 4.1.0
 * @package JotCache
 * @category Joomla 3.2
 * @copyright (C) 2010-2014 Vladimir Kanich
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
JToolBarHelper::title(JText::_('JOTCACHE_EXCLUDE_TITLE'), 'jotcache-logo.gif');
$site_url = JURI::root();
JToolBarHelper::custom('apply', 'apply.png', 'apply.png', 'Apply', false);
JToolBarHelper::spacer();
JToolBarHelper::custom('save', 'save.png', 'save.png', 'Save', false);
JToolBarHelper::spacer();
JToolBarHelper::cancel('display', JText::_('CLOSE'));
JToolBarHelper::spacer();
JToolbarHelper::help('JotCacheHelp', false, $this->url . 'exclusion');
?>
<script language="javascript" type="text/javascript">
  Joomla.submitbutton = function(task){
    if (task == 'save'||task == 'apply') {
      if(!jotcache.pressed()){alert( "<?php echo JText::_('JOTCACHE_EXCLUDE_ERR'); ?>"); return;}
    }
    Joomla.submitform(task, document.getElementById('adminForm'));
  }
  window.addEvent('domready', function(){
    var lang =["<?php echo JText::_('JOTCACHE_EXCLUDE_ERR1'); ?>","<?php echo JText::_('JOTCACHE_EXCLUDE_ERR2'); ?>"];
    jotcache.init(lang);
  });
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
      <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
      <div id="j-main-container">
      <?php endif; ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th nowrap="nowrap" width="120"><input type="checkbox" name="toggle" value=""  onclick="Joomla.checkAll(this);" />&nbsp;<?php echo JText::_('JOTCACHE_EXCLUDE_EXCLUDED'); ?></th>
            <th nowrap="nowrap"><?php echo JText::_('JOTCACHE_EXCLUDE_CN'); ?></th>
            <th><?php echo JText::_('JOTCACHE_EXCLUDE_OPTION'); ?></th>
            <th title="<?php echo JText::_('JOTCACHE_EXCLUDE_VIEWS_DESC'); ?>"><?php echo JText::_('JOTCACHE_EXCLUDE_VIEWS'); ?></th>
          </tr>
        </thead>
        <?php
        $rows = $this->data->rows;
$k = 0;
for ($i = 0, $n = count($rows); $i < $n; $i++) {
$row = $rows[$i];
$checking = array_key_exists($row->option, $this->data->exclude) ? "checked" : "";
$checked = '<input type="checkbox" id="cb' . $i . '" name="cid[]" value="' . $row->id . '" ' . $checking . ' onclick="Joomla.isChecked(this.checked);" />';
?>
          <tr class="<?php echo "row$k"; ?>">
            <td align="center"><?php echo $checked; ?></td>
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->option; ?></td>
            <td width="70%"><?php if ($checking and $this->data->exclude[$row->option] != 1) { ?>
                <input name="<?php echo "ex_$row->option"; ?>" style="width:90%;" value="<?php echo $this->data->exclude[$row->option]; ?>" >
              <?php } else { ?>
                <input name="<?php echo "ex_$row->option"; ?>" style="width:90%;" value="" >
              <?php } ?>
            </td>
          </tr>
          <?php
          $k = 1 - $k;
}?>
      </table>
      <br/>
      <input type="hidden" name="option" value="com_jotcache" />
      <input type="hidden" name="view" value="main" />
      <input type="hidden" name="task" value="exclude" />
      <input type="hidden" name="boxchecked" value="0" />
      </form>