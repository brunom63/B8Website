<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(15, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array('multiselect');

if (isset($_POST['a_remover']) || isset($_POST['a_ativar']) || isset($_POST['a_desativar'])) {
	if (isset($_POST['f_opts'])) {
		$opts_arr = array();
		foreach ($_POST['f_opts'] as $op) {
			$idop = $website->validate_hash('sdk_compras', $op);
			if ($idop) {
				$opts_arr[] = $idop;
			} else {
				$website->page_redirect('atencao.php');
			}
		}
	
		if (count($opts_arr) > 0) {
			if (isset($_POST['a_remover'])) {
				foreach ($opts_arr as $op) {
					$website->remove_db_id('sdk_compras', $op);
				}
		
				$website->str_error = $website->get_string(93);
				$website->str_error_type = 1;
			} else if (isset($_POST['a_ativar'])) {
				foreach ($opts_arr as $op) {
					$sql = "UPDATE sdk_compras SET dt_ativado=1 WHERE ".$website->db_id."=".$op;
					$website->sql_db($sql);
				}
		
				$website->str_error = $website->get_string(94);
				$website->str_error_type = 1;
			} else if (isset($_POST['a_desativar'])) {
				foreach ($opts_arr as $op) {
					$sql = "UPDATE sdk_compras SET dt_ativado=0 WHERE ".$website->db_id."=".$op;
					$website->sql_db($sql);
				}
		
				$website->str_error = $website->get_string(95);
				$website->str_error_type = 1;		
			} else {
				$website->page_redirect('atencao.php');
			}
		
		} else {
			$website->str_error = $website->get_string(97);
		}
	} else {
		$website->str_error = $website->get_string(97);
	}
}

if (isset($_REQUEST['l_status']) && isset($_REQUEST['f'])) {
	$opts_id = $website->validate_hash('sdk_compras', $_REQUEST['f']);
	if (!$opts_id) $website->page_redirect('atencao.php');
	
	$query = "SELECT * FROM sdk_compras WHERE ".$website->db_id."=".$opts_id;
	$result = $website->sql_db($query);
	$line = $website->fetch_array_db($result);
	
	$new_v = ($line['dt_ativado'] == 1) ? 0 : 1;
	
	$sql = "UPDATE sdk_compras SET dt_ativado=".$new_v." WHERE dt_id=".$opts_id;
	$website->sql_db($sql);	
	
	$website->str_error = ($new_v == 1) ? $website->get_string(98) : $website->get_string(99);
	$website->str_error_type = 1;			
}
if (isset($_REQUEST['l_finalizado']) && isset($_REQUEST['f'])) {
	$opts_id = $website->validate_hash('sdk_compras', $_REQUEST['f']);
	if (!$opts_id) $website->page_redirect('atencao.php');
	
	$query = "SELECT * FROM sdk_compras WHERE ".$website->db_id."=".$opts_id;
	$result = $website->sql_db($query);
	$line = $website->fetch_array_db($result);
	
	$new_v = ($line['dt_finalizado'] == 1) ? 0 : 1;
	
	$sql = "UPDATE sdk_compras SET dt_finalizado=".$new_v." WHERE dt_id=".$opts_id;
	$website->sql_db($sql);
	
	$website->str_error = ($new_v == 1) ? $website->get_string(118) : $website->get_string(119);
	$website->str_error_type = 1;			
}

$query = "SELECT * FROM sdk_compras ORDER BY dt_id DESC";
$result = $website->sql_db($query);
while ($line = $website->fetch_array_db($result)) {
	$website->items[] = $line;
}

if (isset($_POST['p_limpar'])) {
	$website->query = "";
	$website->order = 0;
	$website->order_direction = 'desc';
	$website->page = 1;
}
$website->items = $website->search_results($website->items, array('dt_detalhes'));
$website->items = $website->order_results($website->items, array(1 => 'dt_hash', 2 => 'dt_cadastrado', 3 => 'dt_produto', 4 => 'dt_ativado', 5 => 'dt_finalizado',
										6 => 'dt_data_compra', 7 => 'dt_data_entrega', 8 => 'dt_valor', 9 => 'dt_id'));
$website->page_arr = $website->page_results($website->items, 10, 10);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(120); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(15); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-money fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(120); ?></span><?php $website->call_back('main.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form action="compras.php" method="post">
            <input type="hidden" name="p" value="<?php echo $website->page; ?>" />
            <input type="hidden" name="o" value="<?php echo $website->order; ?>" />
            <input type="hidden" name="order" value="<?php echo $website->order_direction; ?>" />
        	<div class="form-inline pull-left rt_a rt_b">
              <div class="form-group">
                <label class="sr-only" for="ps_pesquisar"><?php echo $website->get_string(19); ?></label>
                <input type="text" class="form-control" id="ps_pesquisar" name="q" placeholder="<?php echo $website->get_string(19); ?>" value="<?php echo $website->query; ?>">
              </div>
              <button type="submit" class="btn btn-default" name="p_pesquisar"><?php echo $website->get_string(20); ?></button>
              <button type="submit" class="btn btn-default" name="p_limpar"><?php echo $website->get_string(21); ?></button>
			</div>
        	<div class="pull-right rt_a">
              <button type="submit" class="btn btn-success" name="a_ativar"><?php echo $website->get_string(22); ?></button>
              <button type="submit" class="btn btn-warning" name="a_desativar"><?php echo $website->get_string(23); ?></button>
              <button type="submit" class="btn btn-danger" name="a_remover"><?php echo $website->get_string(25); ?></button>
            </div>
            <div class="clearfix"></div>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-responsive">
                <thead class="rt_tbrw">
                  <tr>
                    <th>
                      <label><input type="checkbox" id="allselect" title="Selecionar todas"></label>                    
                    </th>
                    <th><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=1&order=<?php echo ($website->order == 1 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(125); ?><?php if ($website->order == 1) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=2&order=<?php echo ($website->order == 2 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(121); ?><?php if ($website->order == 2) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=3&order=<?php echo ($website->order == 3 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(122); ?><?php if ($website->order == 2) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=4&order=<?php echo ($website->order == 4 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(27); ?><?php if ($website->order == 3) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=5&order=<?php echo ($website->order == 5 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(114); ?><?php if ($website->order == 3) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=6&order=<?php echo ($website->order == 6 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(123); ?><?php if ($website->order == 4) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=7&order=<?php echo ($website->order == 7 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(124); ?><?php if ($website->order == 5) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=8&order=<?php echo ($website->order == 8 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(116); ?><?php if ($website->order == 6) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="compras.php?<?php echo 'q='.$website->query.'&'; ?>o=9&order=<?php echo ($website->order == 9 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(32); ?><?php if ($website->order == 7) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                  </tr>
                </thead>
                <tbody>
                <?php for ($x = $website->page_arr['pagina_inicio']; $x <= $website->page_arr['pagina_final']; $x++) { $item = $website->items[$x]; ?>
                  <tr>
                    <td><label><input class="checkbx" type="checkbox" name="f_opts[]" value="<?php echo $item['dt_hash']; ?>"></label></td>
                    <td><a href="compras_editar.php?i=<?php echo $item['dt_hash']; ?>"><?php echo $item['dt_hash']; ?></a></td>
                    <td class="text-center"><?php echo $item['dt_cadastrado']; ?></td>
                    <td class="text-center"><?php echo $item['dt_produto']; ?></td>
                    <td class="text-center"><a href="compras.php?<?php echo 'p='.$website->page.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; ?>l_status=&f=<?php echo $item['dt_hash']; ?>"><?php echo ($item['dt_ativado'] == 1) ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'; ?></a></td>
                    <td class="text-center"><a href="compras.php?<?php echo 'p='.$website->page.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; ?>l_finalizado=&f=<?php echo $item['dt_hash']; ?>"><?php echo ($item['dt_finalizado'] == 1) ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'; ?></a></td>
                    <td class="text-center"><?php echo date("d/m/Y", $item['dt_data_compra']); ?></td>
                    <td class="text-center"><?php echo ($item['dt_data_entrega'] != '') ? date("d/m/Y", $item['dt_data_entrega']) : '-'; ?></td>
                    <td class="text-center"><?php echo $item['dt_valor']; ?></td>
                    <td class="text-center"><?php echo $item['dt_id']; ?></td>
                  </tr>
				<?php } ?>
                </tbody>
              </table>
            </div>
            <div class="text-center">
                <ul class="pagination">
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>compras.php?<?php echo 'p=1&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;&nbsp;&laquo;</a></li>
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>compras.php?<?php echo 'p='.($website->page - 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;</a></li>
                  <?php for ($x = $website->page_arr['pagina_bloco_inicio']; $x <= $website->page_arr['pagina_bloco_final']; $x++) { ?>
                  <li<?php if ($website->page == $x) echo ' class="active"'; ?>><a href="<?php if ($website->page == $x) { echo '#'; } else { ?>compras.php?<?php echo 'p='.$x.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>"><?php echo $x; ?></a></li>
                  <?php } ?>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>compras.php?<?php echo 'p='.($website->page + 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;</a></li>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>compras.php?<?php echo 'p='.$website->page_arr['pagina_total'].'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;&nbsp;&raquo;</a></li>
                </ul>
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
