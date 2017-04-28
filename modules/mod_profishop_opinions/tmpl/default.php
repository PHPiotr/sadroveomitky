<?php
defined('_JEXEC') or die;
JHtml::script('jquery.framework');
JHtml::script(JUri::base() . 'templates/profishop/js/cycle2.js');
?>

<div class="reference-module <?php echo $sModuleclassSfx; ?>" style="height: 460px; background: url('/images/strojni-omitky.jpg');">
    <div class="cycle-overlay">
        <div class="cycle-slideshow"
            data-cycle-fx="scrollHorz"
            data-cycle-timeout="10000"
            data-cycle-slides="> span"
            data-cycle-pause-on-hover="true"
            >
        <?php foreach ($opinions as $key => $opinion): ?>
            <?php
            $date = JDate::getInstance($opinion->created_at);
            $created_at_format = $date->format('d.m.Y H:i:s');
            ?>
            <span class="clear clearfix">
                <h2><?php echo $opinion->city; ?></h2>
                <div class='desc' style="height: 300px">
                    <p><?php echo strip_tags($opinion->opinion); ?></p>
                    <p style="font-style: italic"><?php echo $opinion->name; ?>, <?php echo $opinion->city; ?>, <?php echo $created_at_format; ?>
                </div>
            </span>
        <?php endforeach; ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>