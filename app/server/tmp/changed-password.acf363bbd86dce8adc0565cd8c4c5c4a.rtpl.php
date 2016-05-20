<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("header") . ( substr("header",-1,1) != "/" ? "/" : "" ) . basename("header") );?>
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("formatting-header") . ( substr("formatting-header",-1,1) != "/" ? "/" : "" ) . basename("formatting-header") );?>

    This email is being sent to confirm that you have recently changed your password. If you did not initiate this
    password change, please contact an administrator.
    <br>
    <br>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("formatting-footer") . ( substr("formatting-footer",-1,1) != "/" ? "/" : "" ) . basename("formatting-footer") );?>
<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("footer") . ( substr("footer",-1,1) != "/" ? "/" : "" ) . basename("footer") );?>