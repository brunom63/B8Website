<?php
include 'include/include.php';

$website = new B8Admin();

if ($website->p_id == 0) $website->page_redirect('index.php');
if (!in_array(10, $website->user_permissao)) $website->page_redirect('index.php');

$website->modules_list = array('multiselect');

if (isset($_POST['a_remover'])) {
	if (isset($_POST['f_opts'])) {
		$opts_arr = array();
		foreach ($_POST['f_opts'] as $op) {
			$idop = $website->validate_hash('sdk_contatos', $op);
			if ($idop) {
				$opts_arr[] = $idop;
			} else {
				$website->page_redirect('atencao.php');
			}
		}
	
		if (count($opts_arr) > 0) {
			if (isset($_POST['a_remover'])) {
				foreach ($opts_arr as $op) {
					$website->remove_db_id('sdk_contatos', $op);
				}
		
				$website->str_error = $website->get_string(93);
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

$query = "SELECT * FROM sdk_contatos ORDER BY dt_id DESC";
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
$website->items = $website->search_results($website->items, array('dt_email', 'dt_nome', 'dt_titulo', 'dt_mensagem', 'dt_campo1', 'dt_campo2', 'dt_campo3'));
$website->items = $website->order_results($website->items, array(1 => 'dt_email', 2 => 'dt_nome', 3 => 'dt_titulo',
										4 => 'dt_tipo', 5 => 'dt_data', 6 => 'dt_id'));
$website->page_arr = $website->page_results($website->items, 10, 10);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<LINK REL="SHORTCUT ICON" href="favicon.png">
<title><?php echo $website->get_string(73); ?> - <?php echo $website->get_string(8); ?></title>

<?php $website->call_header(); ?>
</head>
<body>

<div class="container-fluid">
  
  <?php $website->call_head(); ?>

  <div class="row">
    
    <?php $website->call_menu(10); ?>
    
    <div class="col-xs-10">
	  <div class="row">
    	<div class="col-xs-12 rt_cnttit">
          <h1><i class="fa fa-comments fa-2x"></i><span class="rt_cnttxt"><?php echo $website->get_string(8); ?></span><?php $website->call_back('main.php'); ?></h1>
        </div>
      </div>
      <div class="row">
    	<div class="col-xs-12 rt_cntbdy">
          <?php if ($website->str_error != "") { ?><p class="rt_error <?php echo ($website->str_error_type == 1) ? 'bg-success' : 'bg-danger'; ?>"><?php echo $website->str_error; ?></p><?php } ?>
          <form action="contatos.php" method="post">
            <input type="hidden" name="p" value="<?php echo $website->page; ?>" />
            <input type="hidden" name="o" value="<?php echo $website->order; ?>" />
            <input type="hidden" name="order" value="<?php echo $website->order_direction; ?>" />
        	<div class="pull-left rt_a">
                <a class="btn btn-primary" href="contatos_enviar.php">+ <?php echo $website->get_string(18); ?></a>
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
                    <th><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=1&order=<?php echo ($website->order == 1 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(37); ?><?php if ($website->order == 1) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=2&order=<?php echo ($website->order == 2 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(36); ?><?php if ($website->order == 2) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=3&order=<?php echo ($website->order == 3 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(38); ?><?php if ($website->order == 3) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=4&order=<?php echo ($website->order == 4 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(39); ?><?php if ($website->order == 4) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=5&order=<?php echo ($website->order == 5 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(30); ?><?php if ($website->order == 5) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                    <th class="text-center"><a class="rg_g" href="contatos.php?<?php echo 'q='.$website->query.'&'; ?>o=6&order=<?php echo ($website->order == 6 && $website->order_direction == 'asc') ? 'desc' : 'asc'; ?>"><?php echo $website->get_string(32); ?><?php if ($website->order == 6) { if ($website->order_direction == 'asc') { ?>&nbsp;<i class="fa fa-sort-amount-asc"></i><?php } else { ?>&nbsp;<i class="fa fa-sort-amount-desc"></i><?php } } ?></a></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                for ($x = $website->page_arr['pagina_inicio']; $x <= $website->page_arr['pagina_final']; $x++) { $item = $website->items[$x];
                    $item['cathash'] = '';
                    if ($item['dt_pai'] != "" && $item['dt_pai'] != 0) {
                        $query = "SELECT dt_hash FROM sdk_contatos WHERE dt_id=".$item['dt_pai'];
                        $result = $website->sql_db($query);
                        $line = $website->fetch_array_db($result);
                        $item['cathash'] = $line['dt_hash'];
                    }
                ?>
                  <tr>
                    <td><label><input class="checkbx" type="checkbox" name="f_opts[]" value="<?php echo $item['dt_hash']; ?>"></label></td>
                    <td><a href="contatos_responder.php?i=<?php echo ($item['dt_pai'] != "" && $item['dt_pai'] != 0) ? $item['cathash'] : $item['dt_hash']; ?>"><?php echo $item['dt_email']; ?></a></td>
                    <td class="text-center"><?php echo $item['dt_nome']; ?></td>
                    <td class="text-center"><?php echo ($item['dt_titulo'] != "") ? $item['dt_titulo'] : '-'; ?></td>
                    <td class="text-center"><?php 
						echo ($item['dt_tipo'] == 1) ? '<i class="fa fa-arrow-down text-info"></i>' : '<i class="fa fa-arrow-up text-success"></i>';
					?></td>
                    <td class="text-center"><?php echo date("d/m/Y", $item['dt_data']); ?></td>
                    <td class="text-center"><?php echo $item['dt_id']; ?></td>
                  </tr>
				<?php } ?>
                </tbody>
              </table>
            </div>
            <div class="text-center">
                <ul class="pagination">
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>contatos.php?<?php echo 'p=1&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;&nbsp;&laquo;</a></li>
                  <li<?php if ($website->page == 1) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == 1) { echo '#'; } else { ?>contatos.php?<?php echo 'p='.($website->page - 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&laquo;</a></li>
                  <?php for ($x = $website->page_arr['pagina_bloco_inicio']; $x <= $website->page_arr['pagina_bloco_final']; $x++) { ?>
                  <li<?php if ($website->page == $x) echo ' class="active"'; ?>><a href="<?php if ($website->page == $x) { echo '#'; } else { ?>contatos.php?<?php echo 'p='.$x.'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>"><?php echo $x; ?></a></li>
                  <?php } ?>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>contatos.php?<?php echo 'p='.($website->page + 1).'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;</a></li>
                  <li<?php if ($website->page == $website->page_arr['pagina_total']) echo ' class="disabled"'; ?>><a href="<?php if ($website->page == $website->page_arr['pagina_total']) { echo '#'; } else { ?>contatos.php?<?php echo 'p='.$website->page_arr['pagina_total'].'&'.'q='.$website->query.'&'.'o='.$website->order.'&'.'order='.$website->order_direction.'&'; } ?>">&raquo;&nbsp;&raquo;</a></li>
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
