<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/web/Public/common/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/web/Public/admin/css/admin.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/web/Public/admin/css/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body class="hold-transition skin-black-light sidebar-mini">
<div class="wrapper">
    <header class="main-header">
    <!-- Logo -->
    <a href="index.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>后台</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">4</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="/web/Public/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/web/Public/admin/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs">Alexander Pierce</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/web/Public/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                            <p>
                                Alexander Pierce - Web Developer
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                        <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo U('Admin/Login/logout');?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
    <!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
	          	<img src="/web/Public/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
	        </div>
	        <div class="pull-left info">
	          	<p>Alexander Pierce</p>
	          	<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	        </div>
		</div>
		<!-- search form -->
		<form action="#" method="get" class="sidebar-form">
	        <div class="input-group">
	          	<input type="text" name="q" class="form-control" placeholder="Search...">
	            <span class="input-group-btn">
	                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
	                </button>
	            </span>
	        </div>
	    </form>
	    <!-- /.search form -->
	    <!-- sidebar menu: : style can be found in sidebar.less -->
	    <ul class="sidebar-menu">
	    	<li class="header">MAIN NAVIGATION</li>
	        <?php if(is_array($__MENU__)): foreach($__MENU__ as $key=>$module): ?><li <?php if(($module['active']) == "1"): ?>class="active treeview"<?php else: ?>class="treeview"<?php endif; ?> >
		          	<a href="#">
		            	<i class="<?php echo ($module['icon']); ?>"></i>
		            	<span><?php echo ($module['title']); ?></span>
		            	<i class="fa fa-angle-left pull-right"></i>
		          	</a>
		          	<ul class="treeview-menu">
		          		<?php if(is_array($module['menus_list'])): foreach($module['menus_list'] as $key=>$menu): ?><li <?php if(($menu['active']) == "1"): ?>class="active"<?php endif; ?> ><a href="/web/index.php/Admin/<?php echo ($menu['controller']); ?>"><i class="<?php echo ($menu['icon']); ?>"></i> <?php echo ($menu['title']); ?></a></li><?php endforeach; endif; ?>
		          	</ul>
		        </li><?php endforeach; endif; ?>
	    </ul>
	</select>
    <!-- /.sidebar -->
</aside>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo (CONTROLLER_NAME); ?><small>Control panel</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php echo (CONTROLLER_NAME); ?></li>
            </ol>
        </section>
        <!-- Main content -->
        
<!-- Main content -->
    <section class="content">
    	  <div class="row">
    		    <div class="col-md-3 col-sm-6 col-xs-12">
        	      <div class="info-box">
          	        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                	  <div class="info-box-content">
                  		<span class="info-box-text">New Members</span>
                  		<span class="info-box-number">2,000</span>
                	  </div>
          	    <!-- /.info-box-content -->
        	      </div>
        	      <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
          	    <div class="info-box">
            	      <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

            	      <div class="info-box-content">
              		      <span class="info-box-text">Likes</span>
              		      <span class="info-box-number">41,410</span>
            	      </div>
            	      <!-- /.info-box-content -->
          	    </div>
          	    <!-- /.info-box -->
            </div>
    	  </div>
    </section>

    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
    <div class="pull-right hidden-xs">
      	<b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-2015 Almsaeed Studio</a>.</strong> All rights reserved.
</footer>

    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="/web/Public/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.5 -->
<script src="/web/Public/common/bootstrap/js/bootstrap.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/web/Public/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="/web/Public/admin/js/app.min.js"></script>
<script src="/web/Public/admin/js/demo.js"></script>

</body>
</html>