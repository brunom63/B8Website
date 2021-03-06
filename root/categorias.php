<?php
include 'include/include.php';

$website = new B8Admin();
$website->db_id = 'dt_cat_id';
$website->db_hash = 'dt_cat_hash';

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(1, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array('multiselect');

if (isset($_POST['a_remover']) || isset($_POST['a_ativar']) || isset($_POST['a_desativar']) || isset($_POST['a_copiar'])) {
	if (isset($_POST['f_opts'])) {
		$opts_arr = array();
		foreach ($_POST['f_opts'] as $op) {
            $idop = $website->validate_hash('sdk_categorias', $op);
			if ($idop) {
				$opts_arr[] = $idop;
			} else {
				$website->page_redirect('atencao.php');
			}
		}
	
		if (count($opts_arr) > 0) {
			if (isset($_POST['a_remover'])) {
				foreach ($opts_arr as $op) {
					$website->remove_db_id('sdk_categorias', $op);
				}
		
				$website->str_error = $website->get_string(93);
				$website->str_error_type = 1;
			} else if (isset($_POST['a_ativar'])) {
				foreach ($opts_arr as $op) {
					$sql = "UPDATE sdk_categorias SET dt_cat_ativado=1 WHERE ".$website->db_id."=".$op;
					$website->sql_db($sql);
				}
		
				$website->str_error = $website->get_string(94);
				$website->str_error_type = 1;
			} else if (isset($_POST['a_desativar'])) {
				foreach ($opts_arr as $op) {
					$sql = "UPDATE sdk_categorias SET dt_cat_ativado=0 WHERE ".$website->db_id."=".$op;
					$website->sql_db($sql);
				}
		
				$website->str_error = $website->get_string(95);
				$website->str_error_type = 1;		
			} else if (isset($_POST['a_copiar'])) {
				foreach ($opts_arr as $op) {
					$query = "SELECT * FROM sdk_categorias WHERE ".$website->db_id."=".$op;
					$result = $website->sql_db($query);
					$line = $website->fetch_array_db($result);
			
					$dates_arr = array();
					$dates_arr['dt_cat_data_inicio'] = ($line['dt_cat_data_inicio'] == "") ? 'NULL' : $line['dt_cat_data_inicio']; 
					$dates_arr['dt_cat_data_fim'] = ($line['dt_cat_data_fim'] == "") ? 'NULL' : $line['dt_cat_data_fim'];
			
					$sql = "INSERT INTO sdk_categorias VALUES (NULL, '".$website->db_hashfield('sdk_categorias')."', '".$line['dt_cat_pai']."', '".$line['dt_cat_titulo'].' - NOVO'."', '".$line['dt_cat_alias']."', '".$line['dt_cat_autor']."', ".$dates_arr['dt_cat_data_inicio'].", ".$dates_arr['dt_cat_data_fim'].", '".$line['dt_cat_ativado']."', '".$line['dt_cat_destaque']."', ".$website->p_id.", ".$website->data_hj.", NULL, '".$line['dt_cat_corpo']."', '".$line['dt_cat_meta_descricao']."', '".$line['dt_cat_meta_tags']."', 0)";
					$website->sql_db($sql);
				}
		
				$website->str_error = $website->get_string(96);
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
	$opts_id = $website->validate_hash('sdk_categorias', $_REQUEST['f']);
	if (!$opts_id) $website->page_redirect('atencao.php');
	
	$query = "SELECT * FROM sdk_categorias WHERE ".$website->db_id."=".$opts_id;
	$result = $website->sql_db($query);
	$line = $website->fetch_array_db($result);
	
	$new_v = ($line['dt_cat_ativado'] == 1) ? 0 : 1;
	
	$sql = "UPDATE sdk_categorias SET dt_cat_ativado=".$new_v." WHERE dt_cat_id=".$opts_id;
	$website->sql_db($sql);	
	
	$website->str_error = ($new_v == 1) ? $website->get_string(98) : $website->get_string(99);
	$website->str_error_type = 1;			
}
if (isset($_REQUEST['l_destaque']) && isset($_REQUEST['f'])) {
	$opts_id = $website->validate_hash('sdk_categorias', $_REQUEST['f']);
	if (!$opts_id) $website->page_redirect('atencao.php');
	
	$query = "SELECT * FROM sdk_categorias WHERE ".$website->db_id."=".$opts_id;
	$result = $website->sql_db($query);
	$line = $website->fetch_array_db($result);
	
	$new_v = ($line['dt_cat_destaque'] == 1) ? 0 : 1;
	
	$sql = "UPDATE sdk_categorias SET dt_cat_destaque=".$new_v." WHERE dt_cat_id=".$opts_id;
	$website->sql_db($sql);
	
	$website->str_error = ($new_v == 1) ? $website->get_string(100) : $website->get_string(101);
	$website->str_error_type = 1;			
}

$query = "SELECT * FROM sdk_categorias ORDER BY dt_cat_id DESC";
$result = $website->sql_db($query);
while ($line = $website->fetch_array_db($result)) {
	
	if ($line['dt_cat_pai'] != "" && $line['dt_cat_pai'] != 0) {
		$query2  = "SELECT * FROM sdk_categorias WHERE dt_cat_id=".$line['dt_cat_pai'];
		$result2 = $website->sql_db($query2);
		$line2 = $website->fetch_array_db($result2);
		
		$line['dt_cat_pai_titulo'] = $line2['dt_cat_titulo'];
	}
	
	$website->items[] = $line;

}

if (isset($_POST['p_limpar'])) {
	$website->query = "";
	$website->order = 0;
	$website->order_direction = 'desc';
	$website->page = 1;
}
$website->items = $website->search_results($website->items, array('dt_cat_titulo', 'dt_cat_corpo'));
$website->items = $website->order_results($website->items, array(1 => 'dt_cat_titulo', 2 => 'dt_cat_ativado', 3 => 'dt_cat_destaque',
										4 => 'dt_cat_pai_titulo', 5 => 'dt_cat_criado_data', 6 => 'dt_cat_hits', 7 => 'dt_cat_id'));
$website->page_arr = $website->page_results($website->items, 10, 10);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(2); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(2); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-folder-open fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(2); ?></span><?php $website->call_back('main.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form action="categorias.php" method="post">
            <input type="hidden" name="p" value="<?php echo $website->page; ?>" />
            <input type="hidden" name="o" value="<?php echo $website->order; ?>" />
            <input type="hidden" name="order" value="<?php echo $website->order_direction; ?>" />
        	<div class="pull-left rt_a">
                <a class="btn btn-primary" href="categorias_inserir.php">+ <?php echo $website->get_string(18); ?></a>
            </div>
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
              <button type="submit" class="btn btn-info" name="a_copiar"><?php echo $website->get_string(24); ?></button>
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
                    <th><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=1&order=<?php echo ($website->order == 1 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(26); ?><?php if ($website->order == 1) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=2&order=<?php echo ($website->order == 2 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(27); ?><?php if ($website->order == 2) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=3&order=<?php echo ($website->order == 3 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(28); ?><?php if ($website->order == 3) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=4&order=<?php echo ($website->order == 4 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(29); ?><?php if ($website->order == 4) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=5&order=<?php echo ($website->order == 5 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(30); ?><?php if ($website->order == 5) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=6&order=<?php echo ($website->order == 6 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(31); ?><?php if ($website->order == 6) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="categorias.php?<?php echo 'q='.$website->query.'&'; ?>o=7&order=<?php echo ($website->order == 7 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(32); ?><?php if ($website->order == 7) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                  </tr>
                </thead>
                <tbody>
                <?php for ($x = $website->page_arr['pagina_inicio']; $x <= $website->page_arr['pagina_final']; $x++) { $item = $website->items[$x]; ?>
                  <tr>
                    <td><label><input class="checkbx" type="checkbox" name="f_opts[]" value="<?php echo $item['dt_cat_hash']; ?>"></label></td>
                    <td><a href="categorias_editar.php?i=<?php echo $item['dt_cat_hash']; ?>"><?php echo $item['dt_cat_titulo']; ?></a></td>
                    <td class="text-center"><a href="categorias.php?<?php echo 'p='.$website->page.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; ?>l_status=&f=<?php echo $item['dt_cat_hash']; ?>"><?php echo ($item['dt_cat_ativado'] == 1) ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'; ?></a></td>
                    <td class="text-center"><a href="categorias.php?<?php echo 'p='.$website->page.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; ?>l_destaque=&f=<?php echo $item['dt_cat_hash']; ?>"><?php echo ($item['dt_cat_destaque'] == 1) ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'; ?></a></td>
                    <td class="text-center"><?php
						if ($item['dt_cat_pai'] != "" && $item['dt_cat_pai'] != 0) {
							echo $item['dt_cat_pai_titulo'];
						} else {
							echo '-';
						}
					?></td>
                    <td class="text-center"><?php echo date("d/m/Y", $item['dt_cat_criado_data']); ?></td>
                    <td class="text-center"><?php echo $item['dt_cat_hits']; ?></td>
                    <td class="text-center"><?php echo $item['dt_cat_id']; ?></td>
                  </tr>
				<?php } ?>
                </tbody>
              </table>
            </div>
            <div class="text-center">
                <ul class="pagination">
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>categorias.php?<?php echo 'p=1&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;&nbsp;&laquo;</a></li>
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>categorias.php?<?php echo 'p='.($website->page - 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;</a></li>
                  <?php for ($x = $website->page_arr['pagina_bloco_inicio']; $x <= $website->page_arr['pagina_bloco_final']; $x++) { ?>
                  <li<?php if ($website->page == $x) echo ' class="active"'; ?>><a href="<?php if ($website->page == $x) { echo '#'; } else { ?>categorias.php?<?php echo 'p='.$x.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>"><?php echo $x; ?></a></li>
                  <?php } ?>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>categorias.php?<?php echo 'p='.($website->page + 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;</a></li>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>categorias.php?<?php echo 'p='.$website->page_arr['pagina_total'].'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;&nbsp;&raquo;</a></li>
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
