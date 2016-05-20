<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("formatting-header") . ( substr("formatting-header",-1,1) != "/" ? "/" : "" ) . basename("formatting-header") );?>
    Files have been uploaded to <?php echo $project->name;?>
    <br>
    <br>

    <ul>
        <?php $counter1=-1; if( isset($files) && is_array($files) && sizeof($files) ) foreach( $files as $key1 => $value1 ){ $counter1++; ?>
        <li><a href="<?php echo $base_url;?>#projects/<?php echo $project->id;?>/files/<?php echo $value1["id"];?>"><?php echo $value1["name"];?></a></li>
        <?php } ?>
    </ul>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("formatting-footer") . ( substr("formatting-footer",-1,1) != "/" ? "/" : "" ) . basename("formatting-footer") );?>
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>