jQuery(document).ready(function() {

    // TODO: Finalitzem les votacions
    // jQuery(this).find('#vote4me_survey-vote-button').click(function() {});


    // Votem un candidat
    jQuery('.vote4me_survey-item').each(function() {
        var vote4me_item = jQuery(this);

        jQuery(this).find('#vote4me_survey-vote-button').click(function() {

            // vote4me_secretaria
            var secretaria_votada = jQuery(vote4me_item).find('.vote4me_secretaria').val();
            console.log(secretaria_votada);

            jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function() {
                // Deshabilitar els butons dels candidats a la mateixa secretaria

                var secretaria = jQuery(this).find('#vote4me_secretaria').val();
                console.log(secretaria);

                if (secretaria_votada == secretaria) {
                    var voteBtn = jQuery(this).find('#vote4me_survey-vote-button');
                    voteBtn.val('...');
                    voteBtn.attr('disabled', 'yes');
                }
            });

            var vote4me_btn = jQuery(this);
            jQuery(vote4me_item).find('.vote4me_spinner').fadeIn();
            //console.log(vote4me_item);

            var data = {
                'action': 'vote4me_vote',
                'option_id': jQuery(vote4me_item).find('#vote4me_survey-item-id').val(),
                'poll_id': jQuery(vote4me_item).find('#vote4me_poll-id').val() // We pass php values differently!
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(vote4me_ajax_obj.ajax_url, data, function(response) {

                var vote4me_json = jQuery.parseJSON(response);

                jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function() {
                    jQuery(this).find('#vote4me_survey-vote-button').addClass('vote4me_scale_hide');
                });

                jQuery(vote4me_item).find('.vote4me_survey-progress-fg').attr('style', 'width:' + vote4me_json.total_vote_percentage + '%');
                jQuery(vote4me_item).find('.vote4me_survey-progress-label').text(vote4me_json.total_vote_percentage + '%');
                jQuery(vote4me_item).find('.vote4me_survey-completes').text(vote4me_json.total_opt_vote_count + ' / ' + vote4me_json.total_vote_count);

                setTimeout(function() {
                    jQuery(vote4me_btn).addClass('vote4me_scale_show');
                    jQuery(vote4me_btn).val("Voted");
                    jQuery(vote4me_btn).toggleClass("vote4me_green_gradient");
                    jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_spinner_stop");
                    jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_drawn");
                }, 300);


            });

        });

    });

    jQuery('.vote4me_pop_close').click(function() {
        jQuery('.vote4me_pop_container').fadeOut();
    });

});