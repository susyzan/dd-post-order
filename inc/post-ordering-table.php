<?php

/**
 * Post Ordering Table Class
 */

if( ! class_exists( 'WP_List_Table' ) ){
	require_once 'class-wp-list-table.php';
}


class Post_Ordering_Table extends WP_List_Table{


	function __construct(){
		parent::__construct(
			array(
				'plural'   => 'wp_order-posts',
				'singular' => 'wp_order-post',
				'ajax'     => false
			)
		);
	}


	function get_columns(){

		$columns = array(
			'post_order'  => __( 'Position' ),
			'post_title'  => __( 'Post Title' ),
			'post_author' => __( 'Author' ),
			'post_date'   => __( 'Date' ),
			'ID'          => __( 'Post ID' ),
		);

		return $columns;
	}

	function get_item_list(){

		$post_type = $_GET[ 'post_type' ] ? $_GET[ 'post_type' ] : 'post';


		$args = array(
			'numberposts' => - 1,
			'orderby'     => 'meta_value',
			'order'       => 'asc',
			'meta_key'    => 'post_order',
			'post_type'   => $post_type
		);

		$postlist = get_posts( $args );

		return $postlist;
	}

	function prepare_items(){
		$columns               = $this->get_columns();
		$this->_column_headers = array( $columns );
		$this->items           = $this->get_item_list();
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows(){

		//Get the records registered in the prepare_items method
		$records = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns ) = $this->get_column_info();

		//Loop for each record
		if( ! empty( $records ) ){

			foreach( $records as $rec ){

				$position = ( $rec->post_order ) ? $rec->post_order : 0;
				$id       = ( $rec->ID ) ? $rec->ID : 'N/A';
				$title    = ( $rec->post_title ) ? $rec->post_title : 'N/A';
				$author   = ( $rec->post_author ) ? $rec->post_author : 'N/A';
				$date     = ( $rec->post_date ) ? $rec->post_date : 'N/A';

				//Open the line
				echo '<tr id="record_' . $id . '" class="draggable-tr" data-order="' . $position . '" data-post-id="' . $id . '" draggable="true">';
				foreach( $columns as $column_name => $column_display_name ){


					//Style attributes for each col
					$class = "class='$column_name column-$column_name'";
					$style = "";
					if( in_array( $column_name ) ){
						$style = ' style="display:none;"';
					}
					$attributes = $class . $style;


					//Display the cell
					switch( $column_name ){
						case "post_order":
							echo '<td ' . $attributes . '>' . stripslashes( $position ) . '</td>';
							break;
						case "post_title":
							echo '<td ' . $attributes . '>' . stripslashes( $title ) . '</td>';
							break;
						case "post_author":
							echo '<td ' . $attributes . '>' . stripslashes( get_the_author_meta( 'nicename', $author ) ) . '</td>';
							break;
						case "post_date":
							echo '<td ' . $attributes . '>' . $date . '</td>';
							break;
						case "ID":
							echo '<td ' . $attributes . '>' . stripslashes( $id ) . '</td>';
							break;
					}
				}

			}

		}
	}

}