<?php
class Adminimize_Templater
{

	/**
	 * Container for common functions
	 * @var object
	 */
	public $common = null;#

	/**
	 * Object with pluginheaders
	 * @var object
	 */
	public $pluginheaders = null;

	/**
	 * Option name
	 * @var string
	 */
	protected $option_name = '';

	/**
	 * Starting delimiter
	 * @var string
	 */
	public $start_delimiter	= '{';

	/**
	 * Ending delimiter
	 * @var string
	 */
	public $end_delimiter	= '}';

	/**
	 * Often used data
	 * @var mixed
	 */
	public static $user_roles       = array();
	public static $user_roles_names = array();

	/**
	 * Initialize some data
	 */
	public function __construct() {

		$this->option_name   = Adminimize_Storage::OPTION_KEY;
		$this->common        = new Adminimize_Common();
		$this->pluginheaders = new Adminimize_PluginHeaders();

		if ( empty( self::$user_roles ) )
			self::$user_roles = $this->common->get_all_user_roles();

		if ( empty( self::$user_roles_names ) )
			self::$user_roles_names = $this->common->get_all_user_roles_names();

	}

	/**
	 *
	 * Replacing values in a format-string
	 * @param string $format
	 * @param array|object $values
	 * @throws Exception
	 * @return string|bool	Returns the formated string or FALSE on failure
	 */
	public function sprintf( $format = '', $values = NULL ) {

		foreach ( $values as $key => $value ) {

			$matches	= array();
			$search_key	= sprintf( '%s%s%s', $this->start_delimiter, $key, $this->end_delimiter );
			$pattern	= sprintf( '/%%%s\[(.*)\]%%/iU', $key );

			// search for the values in format-string. find %key% or %key[format]%
			preg_match_all( $pattern, $format, $matches );

			// the '[format]' part was not found. replace only the key with the value
			if ( empty( $matches[1] ) ) {

				$format = str_replace( $search_key, $value, $format );

			} else {

				foreach ( $matches[1] as $match ) {
					$replace = sprintf( '%' . $match, $value );
					$search = sprintf( '%s%s[%s]%s', $this->start_delimiter, $key, $match, $this->end_delimiter );
					$format = str_replace( $search, $replace, $format );
				}

			}
		}

		return $format;

	}

	/**
	 * Same as sprintf(), but print the result to stdout
	 * @see Adminimize_Templater::printf()
	 * @param string $format
	 * @param string $values
	 */
	public function printf( $format = '', $values = null ) {
		echo $this->sprintf( $format, $values );
	}

	/**
	 * Helper function to create name and id attribute for input fields
	 * @param string $name Name of the field
	 * @return string
	 */
	public function get_name_arg( $name='', $sub_name = '' ) {

		if ( ! empty( $sub_name ) )
			$sub_name = sprintf( '[%s]', $sub_name );

		return sprintf( ' name="%1$s[%2$s]%3$s" id="%1$s-%2$s "', $this->option_name, $name, $sub_name );
	}

	/**
	 * Helper function to create a label tag
	 * @param string $name Name of the label
	 * @return string
	 */
	public function get_label( $name='' ) {
		return sprintf( '<label for="%s-%s">', $this->option_name, $name );
	}

	/**
	 * Generates a button to scroll to the top of page
	 * @return string
	 */
	public function get_scrolltop() {

		$pattern =
<<<PATTERN
		<p>
			<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin: 3px 0 0 30px;">{text}</a>
			<br class="clear" />
		</p>
PATTERN;

		$values = new stdClass();
		$values->text = __( 'scroll to top', $this->pluginheaders->TextDomain );

		return $this->sprintf( $pattern, $values );

	}

	/**
	 * Generates a submit button with selectable name attribute
	 * @param string $name	Name attribute
	 * @return string
	 */
	public function get_submitbutton( $name = '' ) {

		if ( empty( $name ) )
			$name = '_mw_adminimize_save';

		return sprintf(
				'<p id="submitbutton">%s</p>',
				get_submit_button( __('Update Options', $this->pluginheaders->TextDomain ), 'primary', $name, false )
		);

	}

	/**
	 * Generates a submitbutton and a scroll-top button
	 * @return string
	 */
	public function get_widget_bottom() {

		$out = '';
		$out .= $this->get_submitbutton();
		$out .= $this->get_scrolltop();

		return $out;

	}

	/**
	 * Generates a col group for the table head
	 * @return string
	 */
	public function get_colgroups() {

		$pattern = '<colgroup>{groups}</colgroup>';

		$values = new stdClass();
		$values->groups = '';

		foreach ( self::$user_roles_names as $col => $role_name )
			$values->groups .= sprintf( '<col class="col%s">', $col );

		return $this->sprintf( $pattern, $values );

	}

	/**
	 * Generates the table head
	 * @return string
	 */
	public function get_table_header() {

		$pattern =
<<<PATTERN
<thead>
		<tr>
			<th>{upper_left}</th>
			{head_cols}
		</tr>
</thead>
PATTERN;

		$values = new stdClass();
		$values->upper_left = __('Option', $this->pluginheaders->TextDomain );
		$values->head_cols = '';

		foreach ( self::$user_roles_names as $role_name )
			$values->head_cols .= sprintf( '<th>%s<br /><span class="adminimize_table_head_user_role">%s</span></th>', __( 'Deactivate for', $this->pluginheaders->TextDomain ), $role_name );

		return $this->sprintf( $pattern, $values );

	}

	/**
	 * Generates a html table
	 * @param string $option Option-name
	 * @param unknown $elements Elements from database stored with the option-name
	 * @param string $summary Summary-slug for the table
	 * @return string|unknown
	 */
	public function get_table( $option = '', $elements = array(), $summary = '' ) {

		if ( empty( $option ) ) {
			return '';
		} else {
			// first remove any (misplaced) role-pattern and then add the role-pattern at the right place.
			$option = str_replace( '_{role}', '', $option );
			$option .= '_{role}';
		}

		if ( empty( $elements ) )
			$elements = array();

		if ( is_object( $elements ) )
			$elements = (array) $elements;

		$table =
<<<TABLE
<table summary="{summary}" class="widefat">
	{colgroups}
	{thead}
	<tbody>
		{body}
	</tbody>
</table>
TABLE;

		$outer =
<<<OUTER
<tr>
	<td>{title} <span style="color:#ccc; font-weight: 400;">({id})</span></td>
	{inner}
</tr>
OUTER;

		$inner =
<<<INNER
<td class="num">
	<input type="checkbox"{checked} {option} value="{id}" />
</td>
INNER;

		$content            = new stdClass();
		$content->colgroups = $this->get_colgroups();
		$content->thead     = $this->get_table_header();
		$content->summary   = ( empty( $summary ) ) ? 'config_edit' : 'config_edit_' . trim( $summary );
		$content->body      = '';

		foreach ( $elements as $element ) {

			if ( is_object( $element ) )
				$element = (array) $element;

			// skip elements without id
			if ( empty( $element['id'] ) )
				continue;

			$id = &$element['id'];

			$v1        = new stdClass();
			$v1->title = strip_tags( $element['title'] );
			$v1->id    = $id;
			$v1->inner = '';

			if ( empty( $v1->title ) )
				$v1->title = __( 'No Title', $this->pluginheaders->TextDomain );

			foreach ( self::$user_roles as $role ) {

				$option_name = $this->sprintf( $option, array( 'role' => $role) );

				// return array
				$disabled = $this->common->get_option( $option_name );

				$checked = checked(
						true,
						( isset( $disabled[$id] ) && true == $disabled[$id] ),
						false
				);

				$v2          = new stdClass();
				$v2->option  = $this->get_name_arg( $option_name, $id );
				$v2->checked = $checked;
				$v2->id      = $v1->id;

				$v1->inner .= $this->sprintf( $inner, $v2 );

			}

			$content->body .= $this->sprintf( $outer, $v1 );

		}

		$out = '';

		$out .= $this->sprintf( $table, $content );

		return $out;

	}

	/**
	 * Generates table for custom options
	 * @param string $option
	 * @param unknown $elements
	 * @param string $summary
	 * @return string
	 */
	public function get_custom_setings_table( $option = '', $elements = array(), $summary = '' ) {

		$head_pattern =
<<<HEAD
<br style="margin-top: 10px;" />
<table summary="config_edit_{summary}" class="widefat">
	<thead>
		<tr>
			<th>{own_options_str}<br />{id_or_class_str}</th>
			<th><br />{option_str}</th>
		</tr>
	</thead>
	{body}
</table>
HEAD;

		$body_pattern =
<<<BODY
	<tbody>
		<tr valign="top">
			<td colspan="2">
				{table_desc}
			</td>
		</tr>
		<tr valign="top">
			<td>
				<textarea cols="60" rows="3" style="width: 95%" {name_arg_1}>{content_1}</textarea><br />{desc_1}
			</td>
			<td>
				<textarea class="code" cols="60" rows="3" style="width: 95%" {name_arg_2}>{content_2}</textarea><br />{desc_2}
		</tr>
	</tbody>
BODY;

		$v = new stdClass();
		$v->own_options_str = __('Your own options', $this->pluginheaders->TextDomain );
		$v->id_or_class_str = __('ID or class', $this->pluginheaders->TextDomain );
		$v->option_str      = __('Option', $this->pluginheaders->TextDomain );
		$v->table_desc      = __('It is possible to add your own IDs or classes from elements and tags. You can find IDs and classes with the FireBug Add-on for Firefox. Assign a value and the associate name per line.', $this->pluginheaders->TextDomain );
		$v->name_arg_1      = $this->get_name_arg( sprintf( 'custom_%s_options', $option ) );
		$v->content_1       = implode( "\n", $elements['options'] );
		$v->desc_1          = __('Possible nomination for ID or class. Separate multiple nominations through a carriage return.', $this->pluginheaders->TextDomain );
		$v->name_arg_2      = $this->get_name_arg( sprintf( 'custom_%s_values', $option ) );
		$v->content_2       = implode( "\n", $elements['values'] );
		$v->desc_2          = __('Possible IDs or classes. Separate multiple values through a carriage return.', $this->pluginheaders->TextDomain );
		$v->body            =	 $this->sprintf( $body_pattern, $v );

		return $this->sprintf( $head_pattern, $v );

	}

}