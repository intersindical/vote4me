<?php
/*
Plugin Name: vote4me
Plugin Uri: https://educacio.intersindical-csc.cat
Description: Vote plugin based on e-poll by InfoTheme
Author: Gustau Castells (Intersindical-CSC)
Author URI: https://educacio.intersindical-csc.cat
Version: 0.1.49
Tags: WordPress poll, responsive poll, create poll, polls, booth, polling, voting, vote, survey, election, options, contest, contest system, poll system, voting, wp voting, question answer, question, q&a, wp poll system, poll plugin, election plugin, survey plugin, wp poll, user poll, user voting, wp poll, add poll, ask question, forum, poll, voting system, wp voting, vote system, posts, pages, widget.
Text Domain: vote4me
Licence: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Based on: WP Poll Survey & Voting System plugin by InfoTheme
*/

// Plugin Initialization
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

// ACTIVATOR
register_activation_hook(__FILE__, 'vote4me_activate');

// vote4me Activation
// You would use this to provide a function to set up your plugin
// for example, creating some default settings in the options table.
if (!function_exists('vote4me_activate')) {
    function vote4me_activate()
    {
    }
} else {
    $plugin = dirname(__FILE__) . '/vote4me.php';
    deactivate_plugins($plugin);
    wp_die('<div class="plugins"><h2>Vote4me Plugin Activation Error!</h2><p style="background: #ffef80;padding: 10px 15px;border: 1px solid #ffc680;"> \
    We Found that you are using an old Plugin\'s version, Please deactivate that version and then try to re-activate it. \
    Don\'t worry free plugins data will be automatically migrated to this version. Thanks!</p></div>',
    'Plugin Activation Error', array('response'=>200, 'back_link'=>TRUE));
}

// ACTIVATOR
register_activation_hook(__FILE__, 'vote4me_deactivate');

// vote4me Deactivation
if (!function_exists('vote4me_deactivate')) {
    function vote4me_deactivate()
    {
    }
}

// Init plugin
if (!function_exists('vote4me_plugin_conf')) {
    // Global File Attach
    function vote4me_plugin_conf()
    {
        if (!isset($_SESSION)) {
            @session_start();
        }
        // Voting Settings Menu Add
        if (!function_exists('vote4me_settings_menu')) {
            add_action('admin_menu', 'vote4me_settings_menu');
            function vote4me_settings_menu()
            {
                add_submenu_page('tools.php', __('Vote4me'), __('Vote4me'), 'manage_options', 'vote4me_system', 'vote4me_system');
            }

        }
        // Voting Settings Page Callabck
        if (!function_exists('vote4me_system')) {
            function vote4me_system()
            {
                include_once('backend/vote4me_setting.php');
            }
        }
    }
    add_action('init', 'vote4me_plugin_conf');
}

if (!function_exists('vote4me_poll_create_poll') ) {
    function vote4me_poll_create_poll()
    {
        $labels = array(
            'name'                => _x('Poll', 'Post Type General Name', 'vote4me_poll'),
            'singular_name'       => _x('Poll', 'Post Type Singular Name', 'vote4me_poll'),
            'menu_name'           => __('Vote4me', 'vote4me_poll'),
            'name_admin_bar'      => __('Vote4me', 'vote4me_poll'),
            'parent_item_colon'   => __('Parent Poll:', 'vote4me_poll'),
            'all_items'           => __('All Polls', 'vote4me_poll'),
            'add_new_item'        => __('Add New Poll', 'vote4me_poll'),
            'add_new'             => __('Add New', 'vote4me_poll'),
            'new_item'            => __('New Poll', 'vote4me_poll'),
            'edit_item'           => __('Edit Poll', 'vote4me_poll'),
            'update_item'         => __('Update Poll', 'vote4me_poll'),
            'view_item'           => __('View Poll', 'vote4me_poll'),
            'search_items'        => __('Search Poll', 'vote4me_poll'),
            'not_found'           => __('Not found', 'vote4me_poll'),
            'not_found_in_trash'  => __('Not found in Trash', 'vote4me_poll'),
        );
        $args = array(
            'label'               => __( 'Poll', 'vote4me_poll' ),
            'description'         => __( 'Poll Description', 'vote4me_poll' ),
            'labels'              => $labels,
            'supports'            => array( 'title','thumbnail','revisions'),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-chart-pie',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => array('slug' => 'poll'),
            'capability_type'     => 'page',
        );
        register_post_type('vote4me_poll', $args);
        flush_rewrite_rules(true);
    }

    // Hook into the 'init' action
    add_action('init', 'vote4me_poll_create_poll', 0);
}

////////////////////////////////////////////////////////////////////////////////////////////////

//Add vote4me Admin Scripts
if (!function_exists('vote4me_js_register')) {
    
    add_action('admin_enqueue_scripts', 'vote4me_js_register');
    function vote4me_js_register()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('thickbox');

        /* DEBUG
        wp_register_script(
            'vote4me_js',
            plugins_url('/assets/js/vote4me.js', __FILE__),
            array('jquery','media-upload','wp-color-picker','thickbox')
        );
        */
        wp_register_script(
            'vote4me_js',
            plugins_url('/assets/js/vote4me.js?v='.time(), __FILE__),
            array('jquery','media-upload','wp-color-picker','thickbox')
        );
        wp_enqueue_script('vote4me_js');

        wp_register_script(
            'vote4me_contact_builder',
            plugins_url('/assets/js/vote4me_contact_builder.js', __FILE__),
            array('jquery','thickbox')
        );
        wp_enqueue_script('vote4me_contact_builder');
    }
}

// Add ePoll Admin Style
if (!function_exists('vote4me_css_register')) {
    add_action( 'admin_enqueue_scripts', 'vote4me_css_register' );
    function vote4me_css_register()
    {
        wp_register_style('vote4me_css', plugins_url('/assets/css/vote4me.css', __FILE__));
        wp_enqueue_style(array('thickbox','vote4me_css'));
    }
}
    
// Add ePoll Frontend Style
if (!function_exists('vote4me_enqueue_style')) {
    add_action( 'wp_enqueue_scripts', 'vote4me_enqueue_style' );
    function vote4me_enqueue_style()
    {
        wp_enqueue_style(
            'vote4me_style',
            plugins_url('/assets/css/vote4me_frontend.css', __FILE__),
            false
        );
    }
}

// Add ePoll Frontend Script
if (!function_exists('vote4me_enqueue_script')) {
    add_action('wp_enqueue_scripts', 'vote4me_enqueue_script');
    function vote4me_enqueue_script()
    {
        // DEBUG ?v='.time()
        /*
        wp_enqueue_script(
            'vote4me_ajax',
            plugins_url('/assets/js/vote4me_vote.js', __FILE__),
            array('jquery')
        );
        */
        wp_enqueue_script(
            'vote4me_ajax',
            plugins_url('/assets/js/vote4me_vote.js?v='.time(), __FILE__),
            array('jquery')
        );
        wp_localize_script(
            'vote4me_ajax',
            'vote4me_ajax_obj',
            array('ajax_url' => admin_url('admin-ajax.php'))
        );
        wp_enqueue_script(
            'vote4me_script',
            plugins_url('/assets/js/vote4me_frontend.js', __FILE__),
            false
        );
    }
}
    
include_once('backend/vote4me_poll_metaboxes.php');	
include_once('frontend/vote4me_poll.php');

if (!function_exists('get_vote4me_poll_template')) {
    add_filter('single_template', 'get_vote4me_poll_template');
    function get_vote4me_poll_template($single_template)
    {
        global $post;

        if ($post->post_type == 'vote4me_poll') {
            $single_template = dirname(__FILE__) . '/frontend/vote4me_poll-template.php';
        }
        return $single_template;
    }
}

if (!function_exists('ajax_vote4me_vote')) {

    add_action('wp_ajax_vote4me_vote', 'ajax_vote4me_vote');
    add_action('wp_ajax_nopriv_vote4me_vote', 'ajax_vote4me_vote');

    function ajax_vote4me_vote()
    {
        if (isset($_POST['action']) and $_POST['action'] == 'vote4me_vote') {
            @session_start();

            if (isset($_POST['poll_id'])) {
                $poll_id = intval(sanitize_text_field($_POST['poll_id']));
            }

            if (isset($_POST['option_id'])) {
                $option_id = (float) sanitize_text_field($_POST['option_id']);
            }

            if (isset($_POST['voting_code'])) {
                $voting_code = (float) sanitize_text_field($_POST['voting_code']);
            }

            // Validate Poll ID
            if (!$poll_id) {
                $poll_id = '';
                $_SESSION['vote4me_session'] = uniqid();
                $result = array("voting_status"=>"error","message"=>"[Err1] Error en la votació");
                die(json_encode($result));
            }

            // Validate Option ID
            if (!$option_id) {
                $option_id = '';
                $_SESSION['vote4me_session'] = uniqid();
                $result = array("voting_status"=>"error","message"=>"[Err2] Error en la votació");
                die(json_encode($result));
            }

            if (get_post_meta($poll_id, 'vote4me_voting_codes_available')) {
                $voting_codes_available = get_post_meta($poll_id, 'vote4me_voting_codes_available', true);
            }

            if (get_post_meta($poll_id, 'vote4me_voting_codes_used')) {
                $voting_codes_used = get_post_meta($poll_id, 'vote4me_voting_codes_used', true);
            }

            // Validate voting code
            $voting_code_is_ok = false;

            if (!$voting_code) {
                $voting_code = '';
                $_SESSION['vote4me_session'] = uniqid();
            } else {
                $key = array_search($voting_code, $voting_codes_available);
                if ($key !== false) {
                    // La clau està a la llista de claus 'disponibles'
                    $voting_code_is_ok = true;
                }
            }

            if (!$voting_code_is_ok) {
                $result = array("voting_status"=>"error","message"=>"[Err3] Error en la votació");
                die(json_encode($result));
            }
            
            if ($option_id == -1) {
                // Confirmació de la votació

                // Llegim els vots parcials
                if (get_post_meta($poll_id, $votes_options_key, true)) {
                    $votes = get_post_meta($poll_id, $votes_options_key, true);
                } else {
                    $result = array("voting_status"=>"error","message"=>"[Err4] Error en la votació");
                    die(json_encode($result));
                }

                $all_restrictions_ok = false;
                // TODO: Comprovar paritat i territorial

                // $vote4me_options = vote4me_get_options_sorted($poll_id);
                //foreach ($vote4me_options as $options) {
                //    if ($options['id'] == $option_id) {
                //        print_r($options);
                //    }
                //}


                $votes_options_key = 'vote4me_vote_options_'.$poll_id.'_'.$voting_code;
                $votes_session_key = 'vote4me_vote_session_'.$poll_id.'_'.$voting_code;

                if ($all_restrictions_ok) {
                    // L'usuari està confirmant la votació, la clau està dins la llista de claus disponibles
                    // i les restriccions estan bé
                    
                    // Nombre total de vots (no només d'aquest usuari)
                    if (get_post_meta($poll_id, 'vote4me_vote_total_count')) {
                        $total_votes = get_post_meta($poll_id, 'vote4me_vote_total_count', true) + 1;
                    } else {
                        $total_votes = 1;
                    }
                    update_post_meta($poll_id, 'vote4me_vote_total_count', $total_votes);

                    // Comptabilitzem els vots de les secretaries
                    
                    // Confirmem les votacions (afegim -1 al final)
                    array_push($votes, $option_id);
                    update_post_meta($poll_id, $votes_options_key, $votes);

                    // Treiem la clau de la llista de disponibles i la posem a la d'usades
                    unset($voting_codes_available[$voting_code]);
                    array_push($voting_codes_used, $voting_code);
                    update_post_meta(
                        $poll_id,
                        'vote4me_voting_codes_available',
                        $voting_codes_available
                    );
                    update_post_meta(
                        $poll_id,
                        'vote4me_voting_codes_used',
                        $voting_codes_used
                    );

                    $outputdata = array();
                    $outputdata['votes'] = $votes;
                    $outputdata['total_votes'] = $total_votes;
                    $outputdata['voting_status'] = "finished";
                    print_r(json_encode($outputdata));
                }
            } else {
                // Vot parcial (l'usuari ha votat una candidatura però encara no ha finalitzat la votació)
                // Guardem un codi únic a la sessió
                if (!isset($_SESSION[$votes_session_key])) {
                    // És el primer vot, guardem un codi per evitar que pugui votar més d'una vegada
                    $_SESSION[$votes_session_key] = uniqid();
                    // Esborrem totes les possibles votacions prèvies
                    update_post_meta($poll_id, $votes_options_key, array());
                }

                // DEBUG
                //if (!vote4me_check_for_unique_voting($poll_id, $voting_code, $option_id)) {
                if (true) {
                    // Guardem el vot de manera temporal
                    $votes = array();
                    if (get_post_meta($poll_id, $votes_key, true)) {
                        $votes = get_post_meta($poll_id, $votes_key, true);
                        array_push($votes, $option_id);
                        update_post_meta($poll_id, $votes_key, $votes);
                    } else {
                        $result = array("voting_status"=>"error","message"=>"[Err5] Error en la votació");
                        die(json_encode($result));   
                    }

                    $outputdata = array();
                    $outputdata['option_id'] = $option_id;
                    $outputdata['votes'] = $votes;
                    $outputdata['voting_status'] = "voting";
                    print_r(json_encode($outputdata));
                }
            }
        }
        die();
    }
}

// Si retorna true vol dir que ja ha votat
if (!function_exists('vote4me_check_for_unique_voting')) {
    function vote4me_check_for_unique_voting($poll_id, $voting_code)
    {
        $votes_session_key = 'vote4me_vote_session_'.$poll_id.'_'.$voting_code;
        if (isset($_SESSION[$votes_session_key])) {
            return true;
        } else {
            return false;
        }
                
        if (isset($_SESSION['vote4me_session'])) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('vote4me_get_options_sorted')) {
    function vote4me_get_options_sorted($poll_id)
    {
        $vote4me_options = array();
        
        $options = array(
            array('name', 'vote4me_poll_option'),
            array('img', 'vote4me_poll_option_img'),
            array('cover_img', 'vote4me_poll_option_cover_img'),
            array('sex', 'vote4me_poll_option_sex'),
            array('territorial', 'vote4me_poll_option_territorial'),
            array('secretaria', 'vote4me_poll_option_secretaria'),
            array('id','vote4me_poll_option_id'));

        foreach ($options as $option) {
            $info = array();
            $info = get_post_meta($poll_id, $option[1], true);
            $k = 0;
            foreach ($info as $item) {
                $vote4me_options[$k][$option[0]] = $item;
                $k++;
            }
        }
        // print_r($vote4me_options);

        // Sort by secretaries
        usort(
            $vote4me_options, function ($a, $b) {
                return strcmp($a['secretaria'], $b['secretaria']); 
            }
        );

        return $vote4me_options;
    }
}

// Adding Columns to epoll cpt
if (!function_exists('set_custom_edit_vote4me_columns')) {
    add_filter( 'manage_vote4me_poll_posts_columns', 'set_custom_edit_vote4me_columns' );
    function set_custom_edit_vote4me_columns($columns)
    {
        $columns['total_option'] = __('Total Options', 'vote4me');
        $columns['poll_status'] = __('Poll Status', 'vote4me');
        $columns['shortcode'] = __('Shortcode', 'vote4me');
        $columns['view_result'] = __('View Result', 'vote4me');
        return $columns;
    }
}

if (!function_exists('custom_vote4me_poll_column')) {
    // Add the data to the custom columns for the book post type:
    add_action('manage_vote4me_poll_posts_custom_column', 'custom_vote4me_poll_column', 10, 2);
    function custom_vote4me_poll_column($column, $post_id)
    {
        switch ($column) {
        case 'shortcode' :
            $code = '<code>[VOTE4ME id="'.$post_id.'"][/VOTE4ME]</code>';
            if (is_string($code)) {
                echo $code;
            } else {
                _e('Unable to get shortcode', 'vote4me');
            }
            break;
        case 'poll_status' :
            echo "<span style='text-transform:uppercase'>".get_post_meta(get_the_id(), 'vote4me_poll_status', true)."</span>";
            break;
        case 'total_option' :
            if (get_post_meta($post_id,'vote4me_poll_option', true)) {
                $total_opt = sizeof(get_post_meta($post_id, 'vote4me_poll_option', true));
            } else {
                $total_opt = 0;
            }
            echo $total_opt;
            break;
        case 'view_result' :
            echo "<a target='_blank' href='".admin_url('admin.php?page=vote4me_system&view=results&id='.$post_id)."' class='button button-primary'>View (Pro Only)</a>";
            break;
        }
    }
}

if (!function_exists('vote4me_register_button')) {
    function vote4me_register_button( $buttons )
    {
        array_push($buttons, "|", "vote4me");
        return $buttons;
    }
}

if (!function_exists('vote4me_add_plugin')) {
    function vote4me_add_plugin( $plugin_array )
    {
        $plugin_array['vote4me'] = plugins_url( '/assets/js/vote4me_tinymce_btn.js', __FILE__ );
        return $plugin_array;
    }
}

if (!function_exists('vote4me_tinymce_setup')) {
    function vote4me_tinymce_setup()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', 'vote4me_add_plugin');
            add_filter('mce_buttons', 'vote4me_register_button');
        }
    }
    add_action('init', 'vote4me_tinymce_setup');
}

// Shortens a number and attaches K, M, B, etc. accordingly
if (!function_exists('vote4me_number_shorten')) {
    function vote4me_number_shorten($num)
    {
        if ($num>1000) {
                $x = round($num);
                $x_number_format = number_format($x);
                $x_array = explode(',', $x_number_format);
                $x_parts = array('k', 'm', 'b', 't');
                $x_count_parts = count($x_array) - 1;
                $x_display = $x;
                $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
                $x_display .= $x_parts[$x_count_parts - 1];

                return $x_display;

        }
        return $num;
    }
}

include_once('backend/vote4me_widget.php');
?>
