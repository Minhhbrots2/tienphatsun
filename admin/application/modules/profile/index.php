<?php
	$sub = $stdio->GET("sub", "default");
	$act = $stdio->GET("act", "default");
	$clsModule = new Module("profile");
	$clsModule->run($sub, $act);
	#
	$assign_list["sub"] = $sub;
	$assign_list["act"] = $act;
?>