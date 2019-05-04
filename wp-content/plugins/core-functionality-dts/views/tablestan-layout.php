<?php
/**
 * Layout for tablestan shortcode
 *
 * @package     ParkdaleWire\DTS_Core
 * @since       1.0.0
 * @author      thartl
 * @link        https://parkdalewire.com
 * @license     GNU General Public License 2.0+
 */

namespace ParkdaleWire\DTS_Core;

$args = array(
	'post_type' => 'table',
	'meta_query' => array(
		array(
			'key' => 'table_id',
			'value' => $table_id,
			'type' => 'NUMERIC',
			'compare' => '='
		)
	)
);

$this_table_id = get_posts( $args )[0]->ID;

wp_reset_postdata();


$table = get_field( 'dts_table_content', $this_table_id );

$table_class = get_field( 'dts_table_class', $this_table_id );

$table_class = $table_class ? ' class="' . $table_class . '"' : '';

if ( ! empty ( $table ) ) {

	echo '<table' . $table_class . ' border="0">';

	if ( ! empty( $table['caption'] ) ) {

		echo '<caption>' . $table['caption'] . '</caption>';
	}

	if ( ! empty( $table['header'] ) ) {

		echo '<thead>';

		echo '<tr>';

		foreach ( $table['header'] as $th ) {

			echo '<th>';
			echo nl2br( $th['c'] );
			echo '</th>';
		}

		echo '</tr>';

		echo '</thead>';
	}

	echo '<tbody>';

	foreach ( $table['body'] as $tr ) {

		echo '<tr>';

		foreach ( $tr as $td ) {

			echo '<td>';
			echo nl2br( $td['c'] );
			echo '</td>';
		}

		echo '</tr>';
	}

	echo '</tbody>';

	echo '</table>';
}