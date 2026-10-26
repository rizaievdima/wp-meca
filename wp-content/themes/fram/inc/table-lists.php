<?php
/**
 * Lists in Table block cells: a cell only takes inline text in the editor, so lines that
 * start with "- " (separated with Shift+Enter) become a <ul> on the front end.
 * Its CSS is in style.css.
 *
 * @package Fram
 */

defined( 'ABSPATH' ) || exit;

/**
 * Runs every cell of a Table block through fram_table_cell_lists().
 *
 * @param string $block_content Rendered block.
 * @return string
 */
function fram_render_table_lists( $block_content ) {
	if ( ! str_contains( $block_content, '-' ) ) {
		return $block_content;
	}

	return preg_replace_callback(
		'/(<(t[dh])\b[^>]*>)(.*?)(<\/\2>)/is',
		static function ( $cell ) {
			return $cell[1] . fram_table_cell_lists( $cell[3] ) . $cell[4];
		},
		$block_content
	);
}
add_filter( 'render_block_core/table', 'fram_render_table_lists' );

/**
 * Wraps consecutive "- " lines of a cell in <ul>; other lines keep their <br>.
 *
 * @param string $html Cell content.
 * @return string
 */
function fram_table_cell_lists( $html ) {
	$out     = '';
	$in_list = false;

	foreach ( preg_split( '/<br\s*\/?>/i', $html ) as $i => $line ) {
		if ( preg_match( '/^(?:\s|&nbsp;)*-(?:\s|&nbsp;)+(.+)$/s', $line, $item ) ) {
			$out    .= ( $in_list ? '' : '<ul>' ) . '<li>' . $item[1] . '</li>';
			$in_list = true;
			continue;
		}

		// A list is a block, so the line after it needs no <br>.
		if ( $in_list ) {
			$out    .= '</ul>';
			$in_list = false;
		} elseif ( $i > 0 ) {
			$out .= '<br>';
		}
		$out .= $line;
	}

	return $in_list ? $out . '</ul>' : $out;
}
