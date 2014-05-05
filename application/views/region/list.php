<option value=''>请选择地区</option>
<?php foreach($regions as $key => $row):?>
<option value='<?=$row->local_name?>' <?php option_select($selected, $row->local_name) ?> ><?=$row->local_name?></option>
<?php endforeach;?>