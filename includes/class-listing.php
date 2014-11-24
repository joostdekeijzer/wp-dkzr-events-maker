<?php
if(!defined('ABSPATH')) exit;

new Events_Maker_Listing($events_maker);

class Events_Maker_Listing
{
	private $options = array();
	private $recurrences = array();
	private $events_maker;


	public function __construct($events_maker)
	{
		$this->events_maker = $events_maker;

		//settings
		$this->options = $events_maker->get_options();

		//actions
		add_action('after_setup_theme', array(&$this, 'set_recurrences'));
		add_action('manage_posts_custom_column', array(&$this, 'add_new_event_columns_content'), 10, 2);
		add_action('restrict_manage_posts', array(&$this, 'event_filter_dates'));
		add_action('admin_action_duplicate_event', array(&$this, 'duplicate_event'));
		add_action('wp_ajax_events_maker_feature_event', array(&$this, 'feature_event'));

		//filters
		add_filter('manage_edit-event_sortable_columns', array(&$this, 'register_sortable_custom_columns'));
		add_filter('request', array(&$this, 'sort_custom_columns'));
		add_filter('manage_event_posts_columns', array(&$this, 'add_new_event_columns'));
		add_filter('post_row_actions', array(&$this, 'post_row_actions_duplicate'), 10, 2);
	}


	/**
	 * 
	*/
	public function set_recurrences()
	{
		$this->recurrences = $this->events_maker->get_recurrences();
	}


	/**
	 * 
	*/
	public function event_filter_dates()
	{
		if(is_admin())
		{
			global $pagenow;

			$screen = get_current_screen();
			$post_types = apply_filters('em_event_post_type', array('event'));
			
			foreach ($post_types as $post_type)
			{
				if($pagenow === 'edit.php' && $screen->post_type == $post_type && $screen->id === 'edit-'.$post_type)
				{
					echo '
					<label for="emflds">'.__('Start Date', 'events-maker').'</label> <input id="emflds" class="events-datepicker" type="text" name="event_start_date" value="'.(!empty($_GET['event_start_date']) ? esc_attr($_GET['event_start_date']) : '').'" /> 
					<label for="emflde">'.__('End Date', 'events-maker').'</label> <input id="emflde" class="events-datepicker" type="text" name="event_end_date" value="'.(!empty($_GET['event_end_date']) ? esc_attr($_GET['event_end_date']) : '').'" /> ';
				}
			}
		}
	}


	/**
	 * Registers sortable columns
	*/
	public function register_sortable_custom_columns($column)
	{
		$column['start_date'] = 'event_start_date';
		$column['end_date'] = 'event_end_date';

		return $column;
	}


	/**
	 * Sorts custom columns
	*/
	public function sort_custom_columns($qvars)
	{
		if(is_admin() && in_array($qvars['post_type'], apply_filters('em_event_post_type', array('event'))))
		{
			if(!isset($qvars['orderby']))
			{
				switch($this->options['general']['order_by'])
				{
					case 'start':
						$qvars['orderby'] = 'event_start_date';
						break;

					case 'end':
						$qvars['orderby'] = 'event_end_date';
						break;

					case 'publish':
					default:
						$qvars['orderby'] = 'date';
						break;
				}
			}

			if(isset($qvars['orderby']))
			{
				if(in_array($qvars['orderby'], array('event_start_date', 'event_end_date'), true))
				{
					$qvars['meta_key'] = '_'.$qvars['orderby'];
					$qvars['orderby'] = 'meta_value';
				}
				elseif($qvars['orderby'] === 'date')
					$qvars['orderby'] = 'date';
			}

			if(!isset($qvars['order']))
				$qvars['order'] = $this->options['general']['order'];
		}

		return $qvars;
	}


	/**
	 * Adds new event listing columns
	*/
	public function add_new_event_columns($columns)
	{
		unset($columns['date']);
		
		// rename taxonomy names, to make column shorten
		if (isset($columns['taxonomy-event-category']))
			$columns['taxonomy-event-category'] = __('Categories');
		if (isset($columns['taxonomy-event-tag']))
			$columns['taxonomy-event-tag'] = __('Tags');
		
		$columns['start_date'] = __('Start', 'events-maker');
		$columns['end_date'] = __('End', 'events-maker');

		$columns['recurrence'] = '<span class="dash-icon dashicons dashicons-update" title="'.__('Recurrence', 'events-maker').'"></span><span class="dash-title">'.__('Recurrence', 'events-maker').'</span>';

		if($this->options['general']['use_event_tickets'])
			$columns['tickets'] = '<span class="dash-icon dashicons dashicons-tickets" title="'.__('Tickets', 'events-maker').'"></span><span class="dash-title">'.__('Tickets', 'events-maker').'</span>';
		
		$columns['featured'] = '<span class="dash-icon dashicons dashicons-star-filled" title="'.__('Featured', 'events-maker').'"></span><span class="dash-title">'.__('Featured', 'events-maker') . '</span>';

		return $columns;
	}


	/**
	 * Adds new event listing columns content
	*/
	public function add_new_event_columns_content($column_name, $id)
	{
		$mode = !empty($_GET['mode']) ? sanitize_text_field($_GET['mode']) : '';

		switch($column_name)
		{
			case 'start_date':
			case 'end_date':
				$date = get_post_meta($id, '_event_'.$column_name, true);

				echo (em_is_all_day($id) ? date_i18n('Y-m-d', strtotime($date)) : date_i18n('Y-m-d, ' . $this->options['general']['datetime_format']['time'], strtotime($date)));
				break;

			case 'recurrence':
				$recurrence = get_post_meta($id, '_event_recurrence', true);

				echo $this->recurrences[$recurrence['type']];
				break;

			case 'tickets':
				if(!em_is_free($id))
				{
					echo __('paid', 'events-maker').'<br />';

					if($mode === 'excerpt')
					{
						$tickets = get_post_meta($id, '_event_tickets', true);

						foreach($tickets as $ticket)
						{
							echo $ticket['name'].': '.em_get_currency_symbol($ticket['price']).'<br />';
						}
					}
				}
				else
					echo __('free', 'events-maker');
				break;
				
			case 'featured':
				global $pagenow;
				
				$url = wp_nonce_url(admin_url('admin-ajax.php?action=events_maker_feature_event&event_id='.$id), 'events-maker-feature-event', 'em_nonce');
				$is_event_featured = (int)get_post_meta($id, '_event_featured', true);
				
				echo '<a href="'.esc_url($url).'" class="toggle-featured-event" data-post-id="'.$id.'" title="'.__('Toggle featured', 'events-maker') . '">';
				if ($is_event_featured)
					echo '<span class="dashicons dashicons-star-filled" title="' . __('Yes') . '"></span>';
				else
					echo '<span class="dashicons dashicons-star-empty" title="' . __('No') . '"></span>';
				echo '</a>';
				
				break;
		}
	}
	
	
	/**
	 * Add duplicate link to event listing
	*/
	public function post_row_actions_duplicate($actions, $post)
	{
		global $pagenow;
		
		$post_types = apply_filters('em_event_post_type', array('event'));

		if(!in_array($post->post_type, $post_types))
			return $actions;
		
		if(!current_user_can('edit_post', $post->ID))
			return $actions;
		
		// duplicate link
		$actions['duplicate_event'] = '<a class="duplicate-event" title="'.esc_attr__('Duplicate this item', 'events-maker').'" href="'.wp_nonce_url(admin_url($pagenow. '?post='.$post->ID.'&action=duplicate_event'), 'events-maker-duplicate-event', 'em_nonce').'">'.__('Duplicate', 'events-maker').'</a>';
	
		return $actions;
	}


	/**
	 * Duplicate event action in admin
	*/
	public function duplicate_event()
	{
		if (!(isset($_GET['post']) || isset($_POST['post'])) // is post is set
			|| !(isset($_REQUEST['action']) && 'duplicate_event' == $_REQUEST['action']) // is action is set
			|| !isset($_REQUEST['em_nonce']) // is nonce is set
			|| (isset($_REQUEST['em_nonce']) && !wp_verify_nonce(esc_attr($_REQUEST['em_nonce']), 'events-maker-duplicate-event')) // is nonce ok
		)
			wp_die(__('No event to duplicate has been supplied!', 'events-maker'));
	
		// get the original post
		$post_id = (isset($_GET['post']) ? (int)$_GET['post'] : (int)$_POST['post']);
			
		if (empty($post_id))
			wp_die(__('No event to duplicate has been supplied!', 'events-maker'));
		
		if (!current_user_can('edit_post', $post_id))
			wp_die(__('You do not have permission to copy this event.', 'events-maker'));

		$post = get_post($post_id);

		// copy the post and insert it
		if (isset($post) && $post != null)
		{
			$new_id = $this->create_event_duplicate($post);
			
			// redirect to the post list screen
			wp_redirect(admin_url('edit.php?post_type='.$post->post_type));
			exit;	
		}
		else
		{
			wp_die(esc_attr(__('Copy creation failed, could not find original event:', 'events-maker')) . ' ' . htmlspecialchars($post_id));
		}
	}
	
	
	/**
	 * Create an event duplicate function
	*/
	public function create_event_duplicate($post)
	{
		// we don't want to clone revisions
		if ($post->post_type == 'revision') 
			return;
	
		$new_post = apply_filters('em_duplicate_event_args', array(
			'menu_order' => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status' => $post->ping_status,
			'post_author' => $post->post_author,
			'post_content' => $post->post_content,
			'post_excerpt' => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent' => $post->post_parent,
			'post_password' => $post->post_password,
			'post_status' => $post->post_status,
			'post_title' => $post->post_title,
			'post_type' => $post->post_type,
			'post_date' => current_time('mysql'),
			'post_date_gmt' => get_gmt_from_date(current_time('mysql'))
		), $post);
	
		$new_post_id = wp_insert_post($new_post);

		// if the copy is published or scheduled, we have to set a proper slug.
		if ($new_post['status'] == 'publish' || $new_post['status'] == 'future')
		{
			$post_name = wp_unique_post_slug($post->post_name, $new_post_id, $new_post['status'], $post->post_type, $new_post['post_parent']);
	
			$new_post = array();
			$new_post['ID'] = $new_post_id;
			$new_post['post_name'] = $post_name;
			
			// update the post into the database
			wp_update_post($new_post);
		}
		
		// create metadata for the duplicated event
		$this->create_event_duplicate_metadata($new_post_id, $post);
	
		// action hook for developers
		do_action('em_after_duplicate_event', $new_post_id, $post);
	
		return $new_post_id;
	}

	
	/**
	 * Create an event duplicate metadata function
	*/
	public function create_event_duplicate_metadata($new_post_id, $post)
	{
		if (empty($post) || $post == null)
			return;

		// meta keys to be copied
		$meta_keys = apply_filters('em_duplicate_event_meta_keys', get_post_custom_keys($post->ID));

		if (empty($meta_keys))
			return;

		foreach ($meta_keys as $meta_key)
		{
			// meta values to be copied
			$meta_values = apply_filters('em_duplicate_event_meta_values', get_post_custom_values($meta_key, $post->ID));
	
			foreach ($meta_values as $meta_value)
			{
				$meta_value = maybe_unserialize($meta_value);
				// add metadata to duplicated post
				add_post_meta($new_post_id, $meta_key, $meta_value);
			}
		}
	}
	
	
	/**
	 * Feature an event from admin
	 */
	public static function feature_event()
	{
		if (!current_user_can('edit_events'))
			wp_die( _( 'You do not have permission to access this page.', 'events-maker'));

		if (!check_ajax_referer('events-maker-feature-event', 'em_nonce'))
			wp_die(__('You have taken too long. Please go back and retry.', 'events-maker'));

		$post_id = isset($_REQUEST['event_id']) ? (int)$_REQUEST['event_id'] : '';
		$post_types = apply_filters('em_event_post_type', array('event'));
		
		if (!$post_id || !in_array(get_post_type($post_id), $post_types))
			die();

		$featured = (int)get_post_meta($post_id, '_event_featured', true);

		if ($featured === 1)
		{
			update_post_meta($post_id, '_event_featured', 0);
			
			echo json_encode(
				array(
					'ID' => $post_id,
					'status' => 'ok',
					'featured' => false
				)
			);
		}
		else
		{
			update_post_meta($post_id, '_event_featured', 1);
			echo json_encode(
				array(
					'ID' => $post_id,
					'status' => 'ok',
					'featured' => true
				)
			);
		}
		
		do_action('em_after_feature_event');
		
		// JS disabled or JS errors? a dirty trick comes in hand
		if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(esc_attr($_SERVER['HTTP_X_REQUESTED_WITH'])) == 'xmlhttprequest'))
			wp_safe_redirect(remove_query_arg(array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer()));
		
		die();
		
	}
}
?>