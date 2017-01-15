<?php
/**
 * Created by PhpStorm.
 * User: susannazanatta
 * Date: 14/09/2016
 * Time: 10:15 AM
 */

?>

<form class='post-filters custom-post-order-widget-form'>
	<select name="orderby" class="">
		<?php
		$orderby_options = array(
			'post_date'  => 'Order By Date',
			'post_title' => 'Order By Title',
		);
		foreach( $orderby_options as $value => $label ){
			echo "<option value='$value' " . selected( $_GET[ 'orderby' ], $value ) . ">$label</option>";
		}
		?>
	</select>
	<select name="order">
		<?php
		$order_options = array(
			'DESC' => 'Descending',
			'ASC'  => 'Ascending',
		);
		foreach( $order_options as $value => $label ){
			echo "<option value='$value'" . selected( $_GET[ 'order' ], $value ) . ">$label</option>";
		}
		?>
	</select>
	<input type='submit' value='Sort'>
</form>
