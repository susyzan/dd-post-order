<?php
/**
 * Created by PhpStorm.
 * User: susannazanatta
 * Date: 24/08/2016
 * Time: 9:17 AM
 */


$post_type = $_GET[ 'post_type' ] ? $_GET[ 'post_type' ] : 'post';
$post_obj  = get_post_type_object( $post_type );

?>

<div class="wrap">
	<h2>Order <?php echo $post_obj->label ; ?></h2>
	
<?php

$post_ordering_table = new Post_Ordering_Table();
$post_ordering_table->prepare_items();
$post_ordering_table->display();

?>
	</div>
<form action="" method="post">
	<?php submit_button( 'Save Post Order', 'large', 'save-post-order', 'save-post-order' ); ?>
</form>
<?php


