<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(85); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
    <?php $website->call_head(); ?>
  
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6 well rt_logn">
                <p><?php echo $website->get_string(90); ?></p>
                <p><?php echo $website->get_string(91); ?></p>
                <p><?php echo $website->get_string(92); ?></p>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
  
  
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
