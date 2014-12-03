<?php
echo form_open('query_form');
echo form_textarea(array(
		'name' => 'query',
		'id' => 'query_txt'
	));
echo "<br/>";
echo form_submit('search_btn', "Search");
echo form_close();
?>

<div id="result">
</div>