<?php
/**
 * Adds a box to the main column on the Poll edit screens.
 */
function vote4me_metaboxes()
{
    add_meta_box(
        'vote4me_',
        __('Add Poll Options', 'vote4me'),
        'vote4me_metabox_forms',
        'vote4me_poll',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'vote4me_metaboxes');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function vote4me_metabox_forms( $post )
{
    // Add an nonce field so we can check for it later.
    wp_nonce_field('vote4me_metabox_id', 'vote4me_metabox_id_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $vote4me_poll_status = get_post_meta($post->ID, 'vote4me_poll_status', true);
    $vote4me_poll_style = get_post_meta($post->ID, 'vote4me_poll_style', true);
    $vote4me_poll_ui = get_post_meta($post->ID, 'vote4me_poll_ui', true);
    $vote4me_poll_vote_total_count = (int)get_post_meta($post->ID, 'vote4me_vote_total_count', true);
    $vote4me_poll_container_color_primary = get_post_meta($post->ID, 'vote4me_poll_container_color_primary', true);
    $vote4me_voting_codes = get_post_meta($post->ID, 'vote4me_voting_codes', true);

    if (get_post_meta($post->ID, 'vote4me_poll_candidates', true)) {
        $vote4me_poll_candidates = get_post_meta($post->ID, 'vote4me_poll_candidates', true);	
    }

    ?>
    <?php if (($post->post_type == 'vote4me_poll') && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') {?>
        <div class="vote4me_short_code">
            <?php _e('Shortcode for this poll is : <code>[vote4me id="'.$post->ID.'"][/vote4me]</code> (Insert it anywhere in your post/page and show your poll)','vote4me');?>
        </div>
    <?php }?>
    <table class="form-table vote4me_meta_table">
        <tr>
        <td><?php _e('Poll Status','vote4me');?></td>
        <td>
            <select class="widefat" id="vote4me_poll_status" name="vote4me_poll_status" value="" required>
                <option value="live" <?php if($vote4me_poll_status == 'live') echo esc_attr('selected','vote4me');?>>Live</option>
                <option value="end" <?php if($vote4me_poll_status == 'end') echo esc_attr('selected','vote4me');?>>End</option>
            </select>
        </td>
        <td><?php _e('Enable OTP Voting','vote4me');?>
            <!--<span class="vote4meadmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>-->

        <td>	
            <select class="widefat" id="vote4me_poll_ui" name="vote4me_poll_ui"  value="">
                <option value="no" <?php if($vote4me_poll_ui == 'no') echo esc_attr('selected', 'vote4me');?>>No</option>
                <option value="yes" <?php if($vote4me_poll_ui == 'yes') echo esc_attr('selected', 'vote4me');?>>Yes</option>
            </select>
        </td>
        </tr>
        <tr>
        <td><?php _e('Poll Style', 'vote4me');?></td>
        <td>

            <select class="widefat" id="vote4me_poll_style" name="vote4me_poll_style" value="" required/>
                <option value="grid" <?php if ($vote4me_poll_style == 'grid') echo esc_attr('selected', 'vote4me');?>>Grid</option>
                <option value="list" <?php if ($vote4me_poll_style == 'list') echo esc_attr('selected', 'vote4me');?>>List</option>
            </select>
        </td>
        <td><?php _e('Multi Voting ', 'vote4me');?>
        <!--<span class="vote4meadmin_pro_badge" style="top: 2px; position: relative;"><i class="dashicons dashicons-star-empty"></i> Premium Only</span></td>-->
            <td>
            <select name="vote4me_multivoting" class="widefat">
                <option value="no" <?php if ($vote4me_multivoting == 'no') echo esc_attr('selected', 'vote4me');?>>No</option>
                <option value="yes" <?php if ($vote4me_multivoting == 'yes') echo esc_attr('selected', 'vote4me');?>>Yes</option>
            </select>
        </td>
        </tr>
        <tr>
            <td><?php _e('Container Gradient', 'vote4me'); ?></td>
            <td colspan="1">
                <input type="text" class="widefat vote4me_color-field"
                name="vote4me_poll_container_color_primary" value=""/>
            </td>
        </tr>

        <tr>
        <td><?php _e('Codis de votació', 'vote4me'); ?></td>
        <td><textarea name="vote4me_voting_codes" id="vote4me_voting_codes" rows="20" cols="32"><?php
        foreach ($vote4me_voting_codes as $code) {
            echo $code."\n";
        }?></textarea>
        </td>
        </tr>
        
    </table>
    
    <table class="form-table" id="vote4me_append_option_filed">
    <?php if (!empty($vote4me_poll_candidates)) :
        $i=0;
        foreach($vote4me_poll_candidates as $vote4me_candidate):
            $pollKEYIt = (float)$vote4me_candidate['id'];
            $vote4me_poll_vote_count = (int)get_post_meta($post->ID, 'vote4me_vote_count_'.$pollKEYIt, true);
            
            if (!$vote4me_poll_vote_count) {
                $vote4me_poll_vote_count = 0;
            }
    ?>
            <tr class="vote4me_append_option_filed_tr">
            <td>
            <table class="form-table">
                <tr>
                    <td><?php _e('Option Name', 'vote4me');?></td>
                    <td>
                        <input type="text" class="widefat" id="vote4me_poll_option_name" name="vote4me_poll_option_name[]" value="<?php
                        echo esc_attr($vote4me_candidate['name'], 'vote4me');?>" required/>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Image', 'vote4me');?></td>
                    <td><input type="url" class="widefat" id="vote4me_poll_option_img" name="vote4me_poll_option_img[]" value="<?php
                    if (!empty($vote4me_candidate['img'])) {
                        echo esc_attr($vote4me_candidate['img'], 'vote4me');
                    }?>"/>
                        <input type="hidden" name="vote4me_poll_option_id[]" id="vote4me_poll_option_id" value="<?php
                        echo esc_attr($vote4me_candidate['id'], 'vote4me');?>"/>
                    </td>
                    <td>
                        <input type="button" class="button" id="vote4me_poll_option_btn" name="vote4me_poll_option_btn" value="<?php _e('Upload', 'vote4me');?>">
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Imatge de fons', 'vote4me');?></td>
                    <td><input type="url" class="widefat" id="vote4me_poll_option_cover_img" name="vote4me_poll_option_cover_img[]" value="<?php
                    if (!empty($vote4me_candidate['cover_img'])) {
                        echo esc_attr($vote4me_candidate['cover_img'], 'vote4me');
                    }?>"/>
                    </td>
                    <td>
                        <input type="button" class="button" id="vote4me_poll_option_ci_btn" name="vote4me_poll_option_ci_btn" value="<?php _e('Upload', 'vote4me');?>">
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Sexe', 'vote4me');?></td>
                    <td>
                        <select class="widefat" id="vote4me_poll_option_sex" name="vote4me_poll_option_sex[]" value="<?php
                        echo esc_attr($vote4me_candidate['sex'], 'vote4me');?>" required>
                            <option value="male" <?php if ($vote4me_candidate['sex'] == 'male') echo esc_attr('selected', 'vote4me');?>>Male</option>
                            <option value="female" <?php if ($vote4me_candidate['sex'] == 'female') echo esc_attr('selected', 'vote4me');?>>Female</option>
                            <option value="other" <?php if ($vote4me_candidate['sex'] == 'other') echo esc_attr('selected', 'vote4me');?>>Other</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Territorial', 'vote4me');?></td>
                    <td>
                        <select class="widefat" id="vote4me_poll_option_territorial" name="vote4me_poll_option_territorial[]" value="<?php
                        echo esc_attr($vote4me_candidate['territorial'], 'vote4me');?>" required>
                            <option value="Baix LLobregat" <?php if ($vote4me_candidate['territorial'] == 'Baix LLobregat') echo esc_attr('selected', 'vote4me');?>>Baix LLobregat</option>
                            <option value="Barcelona comarques" <?php if ($vote4me_candidate['territorial'] == 'Barcelona comarques') echo esc_attr('selected', 'vote4me');?>>Barcelona comarques</option>
                            <option value="Catalunya central" <?php if ($vote4me_candidate['territorial'] == 'Catalunya central') echo esc_attr('selected', 'vote4me');?>>Catalunya central</option>
                            <option value="Girona" <?php if ($vote4me_candidate['territorial'] == 'Girona') echo esc_attr('selected', 'vote4me');?>>Girona</option>
                            <option value="Lleida" <?php if ($vote4me_candidate['territorial'] == 'Lleida') echo esc_attr('selected', 'vote4me');?>>Lleida</option>
                            <option value="Maresme / Vallès Oriental" <?php if ($vote4me_candidate['territorial'] == 'Maresme / Vallès Oriental') echo esc_attr('selected', 'vote4me');?>>Maresme / Vallès Oriental</option>
                            <option value="Tarragona" <?php if ($vote4me_candidate['territorial'] == 'Tarragona') echo esc_attr('selected', 'vote4me');?>>Tarragona</option>
                            <option value="Terres de l\'Ebre" <?php if ($vote4me_candidate['territorial'] == 'Terres de l\'Ebre') echo esc_attr('selected', 'vote4me');?>>Terres de l'Ebre</option>
                            <option value="Vallès Occidental" <?php if ($vote4me_candidate['territorial'] == 'Vallès Occidental') echo esc_attr('selected', 'vote4me');?>>Vallès Occidental</option>
                            <option value="Barcelona" <?php if ($vote4me_candidate['territorial'] == 'Barcelona') echo esc_attr('selected', 'vote4me');?>>Barcelona</option>
                            <option value="Catalunya" <?php if ($vote4me_candidate['territorial'] == 'Catalunya') echo esc_attr('selected', 'vote4me');?>>Catalunya</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php _e('Secretaria', 'vote4me');?></td>
                    <td>
                        <select class="widefat" id="vote4me_poll_option_secretaria" name="vote4me_poll_option_secretaria[]" value="<?php
                        echo esc_attr($vote4me_candidate['secretaria'], 'vote4me');?>" required>
                            <option value="Organització" <?php if ($vote4me_candidate['secretaria'] == 'Organització') echo esc_attr('selected','vote4me');?>>Organització</option>
                            <option value="Acció sindical" <?php if ($vote4me_candidate['secretaria'] == 'Acció sindical') echo esc_attr('selected','vote4me');?>>Acció sindical</option>
                            <option value="Comunicació" <?php if ($vote4me_candidate['secretaria'] == 'Comunicació') echo esc_attr('selected','vote4me');?>>Comunicació</option>
                            <option value="Política educativa i igualtat" <?php if ($vote4me_candidate['secretaria'] == 'Política educativa i igualtat') echo esc_attr('selected','vote4me');?>>Política educativa i igualtat</option>
                            <option value="Formació" <?php if ($vote4me_candidate['secretaria'] == 'Formació') echo esc_attr('selected','vote4me');?>>Formació</option>
                            <option value="Ensenyament concertat i privat" <?php if ($vote4me_candidate['secretaria'] == 'Ensenyament concertat i privat') echo esc_attr('selected','vote4me');?>>Ensenyament concertat i privat</option>
                            <option value="Juntes de personal" <?php if ($vote4me_candidate['secretaria'] == 'Juntes de personal') echo esc_attr('selected','vote4me');?>>Juntes de personal</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="button" class="button" id="vote4me_poll_option_rm_btn" name="vote4me_poll_option_rm_btn" value="Remove This Option">
                    </td>
                </tr>
            </table>
            </td>
            </tr>
            <?php 
            $i++;
        endforeach;
    endif; ?>
    </table>
    
    <table class="form-table">
        <tr>
            <td><button type="button" name="" class="button vote4me_add_option_btn" id="">
            <i class="dashicons-before dashicons-plus-alt"></i>
            <?php _e('Add Option','vote4me');?></button></td>
        </tr>
    </table>   
   
    <?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function vote4me_save_options( $post_id )
{
    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['vote4me_metabox_id_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['vote4me_metabox_id_nonce'], 'vote4me_metabox_id')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && $_POST['post_type'] == 'vote4me_poll') {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    } else {
        return;
    }

    // Sanitize user input & Update the meta field in the database.
    
    // Updating Poll status
    if (isset($_POST['vote4me_poll_status'])) {
        $vote4me_poll_status =  sanitize_text_field($_POST['vote4me_poll_status']);
        update_post_meta($post_id, 'vote4me_poll_status', $vote4me_poll_status);
    }

    // Updating Poll UI
    if (isset($_POST['vote4me_poll_ui'])) {
        $vote4me_poll_ui = sanitize_text_field($_POST['vote4me_poll_ui']);
        update_post_meta($post_id, 'vote4me_poll_ui', $vote4me_poll_ui);
    }

    // Updating Poll Style
    if (isset($_POST['vote4me_poll_style'])) {
        $vote4me_poll_style =  sanitize_text_field($_POST['vote4me_poll_style']);
        update_post_meta($post_id, 'vote4me_poll_style', $vote4me_poll_style);
    }

    // Updating Poll Container Primary Color
    if (isset($_POST['vote4me_poll_container_color_primary'])) {
        $vote4me_poll_ui =  sanitize_text_field($_POST['vote4me_poll_container_color_primary']);
        update_post_meta($post_id, 'vote4me_poll_container_color_primary', $vote4me_poll_ui);
    }

    // Updating voting codes
    if (isset($_POST['vote4me_voting_codes'])) {
        $vote4me_voting_codes =  sanitize_text_field($_POST['vote4me_voting_codes']);
        update_post_meta($post_id, 'vote4me_voting_codes', $vote4me_voting_codes);
    }

    // Updating Poll Container Secondary Color
    
    update_poll_candidates($post_id);
}

function update_poll_candidates($post_id)
{
    $options = array(
        array('id','vote4me_poll_option_id'),
        array('name', 'vote4me_poll_option_name'),
        array('img', 'vote4me_poll_option_img'),
        array('cover_img', 'vote4me_poll_option_cover_img'),
        array('sex', 'vote4me_poll_option_sex'),
        array('territorial', 'vote4me_poll_option_territorial'),
        array('secretaria', 'vote4me_poll_option_secretaria')
    );
    
    $vote4me_poll_candidates = array();

    $is_blank_inserted = false;
    
    foreach ($options as $key) {
        $info = $_POST[$key[1]];
        $k = 0;
        foreach ($info as $item) {
            $item = sanitize_text_field($item);
            $vote4me_poll_candidates[$k][$key[0]] = $item;
            $k++;

            if ($item == "Vot en blanc") {
                $is_blank_inserted = true;
            }
        }
    }

    if (!$is_blank_inserted) {
        // Afegim el vot en blanc a totes les secretaries ////////////////////////////////////////////
        // TODO: Afegir una opció de configuració per poder decidir si s'afegeix la opció de vot en blanc o no
        // TODO: Afegir una opció de configuració per poder seleccionar la imatge de vot en blanc
        $img_url = "https://educacio.intersindical-csc.cat/wp-content/uploads/2020/10/votenblanc.jpg";

        // TODO: Afegir una opció de configuració per poder afegir les secretaries (ara ho deixem fix)
        $secretaries = array(
            "Organització",
            "Acció sindical",
            "Comunicació",
            "Política educativa i igualtat",
            "Formació",
            "Ensenyament concertat i privat",
            "Juntes de personal"
        );

        $blank = array(
            'id'=>0,
            'name'=> "Vot en blanc",
            'img'=> $img_url,
            'cover_img'=>"",
            'sex'=>'other',
            'territorial'=>"Catalunya",
            'secretaria'=>""
        );

        foreach ($secretaries as $secretaria) {
            $blank['id'] = uniqid();
            $blank['secretaria'] = $secretaria;
            array_push($vote4me_poll_candidates, $blank);
        }
    }

    update_post_meta($post_id, 'vote4me_poll_candidates', $vote4me_poll_candidates);
}

add_action('save_post', 'vote4me_save_options');
