<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(8, $website->user_permissao)) $website->page_redirect('index.php');
	
$website->item_id = $website->db_validate('sdk_newsletters');

$website->modules_list = array();
	
$query = "SELECT * FROM sdk_newsletters WHERE ".$website->db_id."=".$website->item_id;
$result = $website->sql_db($query);
$website->item = $website->fetch_array_db($result);
	
$query = "SELECT * FROM sdk_admlogin WHERE dt_id=".$website->item['dt_criado_por'];
$result = $website->sql_db($query);
$criado = $website->fetch_array_db($result);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(6); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(9); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-envelope fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(6); ?></span> <small><?php echo $website->get_string(110); ?></small><?php $website->call_back('newsletters.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form role="form" method="post" action="newsletters_editar.php?i=<?php echo $website->item[$website->db_hash]; ?>">
		  <div class="row">
			<div class="col-md-6">
                <div class="form-horizontal rt_c">
                  <?php echo ($website->item['dt_relatorio'] != "") ? $website->item['dt_relatorio'] : $website->get_string(111); ?>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-horizontal rt_c">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(32); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $website->item['dt_id']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(31); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $website->item['dt_hits']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(48); ?></label>
                    <div class="col-sm-9">
                      <p class="form-control-static"><?php echo $criado['dt_campo1']; ?></p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(49); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static"><?php echo date("d/m/y H:i", $website->item['dt_criado_data']); ?></p>
                    </div>
                    <label class="col-sm-3 control-label"><?php echo $website->get_string(50); ?></label>
                    <div class="col-sm-3">
                      <p class="form-control-static"><?php if ($website->item['dt_alterado_data'] != "") echo date("d/m/y H:i", $website->item['dt_alterado_data']); ?></p>
                    </div>
                  </div>
                </div>
            </div>
		  </div>
          </form>
        </div>
      </div>
    </div>  
  </div>
</div>
 
<?php $website->call_scripts(); ?>
</body>
</html>
