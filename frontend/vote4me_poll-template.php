<?php

if (!isset($_SESSION)) {
    session_start();
}

get_header();

while ( have_posts() ) : the_post();

    $vote4me_poll_candidates = get_post_meta(get_the_id(), 'vote4me_poll_candidates', true);

    // Sort by secretaries
    usort(
        $vote4me_poll_candidates, function ($a, $b) {
            return strcmp($a['secretaria'], $b['secretaria']); 
        }
    );

    // Common poll parameters
    $vote4me_poll_status = get_post_meta(get_the_id(), 'vote4me_poll_status', true);
    $vote4me_poll_style = get_post_meta(get_the_id(), 'vote4me_poll_style', true);
    $vote4me_poll_vote_total_count = (int)get_post_meta(get_the_id(), 'vote4me_vote_total_count', true);
    $vote4me_poll_container_color_primary = get_post_meta($post->ID, 'vote4me_poll_container_color_primary', true);

    $vote4me_voting_codes = get_post_meta(get_the_id(), 'vote4me_voting_codes', true);
    //$vote4me_voting_codes_available = get_post_meta(get_the_id(), 'vote4me_voting_codes_available', true);
    $vote4me_voting_codes_used = get_post_meta(get_the_id(), 'vote4me_voting_codes_used', true);
    
    $vote4me_secretaria_title = "";
    ?>
    <div class="vote4me_container"
    <?php
    if ($vote4me_poll_container_color_primary) {
        echo ' style="background: -webkit-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
        background: -o-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
        background: linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;"';
    } ?>>
    <h1 class="vote4me_title">
    <span class="vote4me_title_exact"><?php the_title();?></span>
    <span class="vote4me_survey-stage">
    <span class="vote4me_stage vote4me_live vote4me_active" <?php if($vote4me_poll_status !== 'live') echo 'style="display:none;"';?>>Votació activa</span>
    <span class="vote4me_stage vote4me_ended vote4me_active" <?php if($vote4me_poll_status !== 'end') echo 'style="display:none;"';?>>Votació tancada</span>
    </span>
    </h1>
    <div class="vote4me_inner">

    <div class="vote4me_voting_code_box">
    <form>
    <span class="vote4me_voting_code_label">Codi de votació:</span>
    <input type="text" name="vote4me_voting_code" id="vote4me_voting_code">
    <input type="button" name="vote4me_voting_code_btn" id="vote4me_voting_code_btn" value="Vull votar!">
    <input type="hidden" name="vote4me_poll-id" id="vote4me_poll-id" value="<?php echo get_the_id();?>">
    </form>
    </div>

    <ul class="vote4me_surveys vote4me_surveys_hide <?php if ($vote4me_poll_style == 'list') echo 'vote4me_list'; else echo 'vote4me_grid';?>">
    <?php
    if (isset($vote4me_poll_candidates[0]['name'])) {
        foreach($vote4me_poll_candidates as $vote4me_candidate):
            $vote4me_poll_vote_count = (int)get_post_meta(
                get_the_id(),
                'vote4me_vote_count_'.strval($vote4me_candidate['id']), true
            );
            //print_r($vote4me_candidate['id']);
            $vote4me_poll_vote_percentage = 0;
            if ($vote4me_poll_vote_count == 0) {
                $vote4me_poll_vote_percentage = 0;
            } else {
                $vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_count*100/$vote4me_poll_vote_total_count; 
            }
            $vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_percentage;
            ?>

            <?php
            if ($vote4me_candidate['secretaria'] != $vote4me_secretaria_title) {
                $vote4me_secretaria_title = $vote4me_candidate['secretaria'];
                ?>
                <hr />
                <div class="vote4me_secretaria_title">
                    <?php echo $vote4me_secretaria_title;?>
                </div>
                <?php
            }
            ?>

            <li class="vote4me_survey-item">
            <div class="vote4me_survey-item-inner vote4me_card_front">
            <div class="vote4me_big_cover">
                <?php
                if ($vote4me_candidate["cover_img"]) {
                    if (isset($vote4me_candidate["cover_img"])) {
                        echo '<img src="'.$vote4me_candidate["cover_img"].'">';
                    }
                }
                ?>
            </div>
            
            <?php
            if (isset($vote4me_candidate["img"])) { ?>
                <div class="vote4me_survey-country vote4me_grid-only">
                <img src="<?php echo $vote4me_candidate["img"];?>">
                <div class="vote4me_spinner">
                <svg version="1.1" id="vote4me_tick" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 37 37" xml:space="preserve">
                <path class="vote4me_circ vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;"
                    d="M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z" />
                <polyline class="vote4me_tick vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;"
                    points="11.6,20 15.9,24.2 26.4,13.8" />
                </svg>
                </div>
                </div>
            <?php } ?>

            <div class="vote4me_survey-name">
                <?php echo $vote4me_candidate['name'];?>
            </div>
            <div class="vote4me_survey-territorial">
                <?php echo $vote4me_candidate["territorial"];?>
            </div>
            <div class="vote4me_survey-secretaria">
                <?php echo $vote4me_candidate["secretaria"];?>
            </div>

            <?php
            // DEBUG
            // if (vote4me_check_for_unique_voting(get_the_id(), dkñadfjklsdfklñajs $vote4me_candidate['voting_code'], $vote4me_candidate['id'])) {
            if (false) {
                ?>
                <div class="vote4me_survey-item-action vote4me_survey-item-action-disabled"></div>
                <span style="border-top:1px solid #ccc;border-bottom:1px solid #ccc; padding:0px; margin:5px; display:inline-block; color: #fc6462;">
                    Ja has votat!
                </span>
                <?php
            } else { ?>
                <div class="vote4me_survey-item-action">
                <form action="" name="vote4me_survey-item-action-form" class="vote4me_survey-item-action-form">
                    <input type="hidden" name="vote4me_survey-item-id" class="vote4me_survey-item-id" value="<?php echo $vote4me_candidate['id'];?>">
                    <input type="hidden" name="vote4me_secretaria" class="vote4me_secretaria" value="<?php echo $vote4me_candidate['secretaria'];?>">
                    <input type="button" name="vote4me_survey-vote-button" class="vote4me_survey-vote-button" class="vote4me_orange_gradient" value="Vota">
                </form>
                </div>
            <?php } ?>          

            <div class="vote4me_pull-right">
                <span class="vote4me_survey-progress">
                    <span class="vote4me_survey-progress-labels">
                        <input type="hidden" name="vote4me_poll_e_vote_count" id="vote4me_poll_e_vote_count" value="<?php echo $vote4me_poll_vote_count; ?>"/>
                    </span>
                </span>
            </div>
            </div>
            </li>
            <?php
        endforeach;
        ?>
        <br><hr><br>
        <div class="vote4me_survey-item-action-final">
        <form action="" name="vote4me_survey-item-action-form-final" class="vote4me_survey-item-action-form-final">
            <input type="button" name="vote4me_survey-vote-button-final" id="vote4me_survey-vote-button-final"
                class="vote4me_red_gradient" value="Fes clic aquí per confirmar les votacions!">
        </form>
        </div>
        <?php
        echo '</ul> <div style="clear:both;"></div>';
    } else {
        if (current_user_can('author') || current_user_can('editor') || current_user_can('administrator')) {
            $link = get_edit_post_link(get_the_id());
            echo '<p class="vote4me_short_code" style="text-align:center;">No hi ha candidats per votar!</p><br>';
            echo '<a href="'.$link.'" class="vote4me_survey-notfound-button" style="width:auto;max-width:100%;">Edita els candidats</a>';
        } else {
            echo '<p class="vote4me_short_code">Aquesta elecció encara no està llesta. Posa\'t en contacte amb l\'administrador</p>';
        }
    }?>  
    </div>
    <div class="vote4me_powered_by">
    <a href="https://educacio.intersindical-csc.cat/"
        target="_blank" rel="nofollow">
        Sectorial d'educació de la Intersindical-CSC</a>
    </div>
    </div>
<?php endwhile;

get_footer();
?>
