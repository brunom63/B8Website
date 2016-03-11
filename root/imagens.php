<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(3, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(5); ?></title>

<?php $website->call_header(); ?>
<script type="text/javascript" src="style/scripts/ckfinder/ckfinder.js"></script> <?php // CKFinder ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(4); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-camera fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(5); ?></span><?php $website->call_back('main.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <script type="text/javascript"> <?php // CKFinder ?>
			  var finder = new CKFinder();
			  finder.basePath = '/root/style/scripts/ckfinder/';
			  finder.language = '<?php echo $website->get_string(80); ?>';
			  finder.create();
		  </script>
        </div>
      </div>
    </div>  
  </div>
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
