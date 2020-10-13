jQuery(document).ready(function() {

    // TODO: Finalitzem les votacions
    // jQuery(this).find('#vote4me_survey-vote-button').click(function() {});

    // vote4me_survey-item-id = option_id
    // Votem un candidat
    jQuery('.vote4me_survey-item').each(function() {
        var vote4me_item = jQuery(this);

        jQuery(this).find('#vote4me_survey-vote-button').click(function() {

            var voting_code = jQuery(document).find('#vote4me_voting_code').val();
            console.log("voting_code: " + voting_code);
            if (voting_code == "") {
                alert("Has d'entrar el codi de votació!")
                return;
            }

            // vote4me_secretaria
            var secretaria_votada = jQuery(vote4me_item).find('#vote4me_secretaria').val();
            console.log("Secretaria votada:" + secretaria_votada);

            // Deshabilitar els butons dels candidats a la mateixa secretaria
            jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function() {
                var secretaria = jQuery(this).find('#vote4me_secretaria').val();
                console.log("Secretaria:" + secretaria);
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
                'voting_code': voting_code,
                'option_id': jQuery(vote4me_item).find('#vote4me_survey-item-id').val(),
                'poll_id': jQuery(vote4me_item).find('#vote4me_poll-id').val() // We pass php values differently!
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(vote4me_ajax_obj.ajax_url, data, function(response) {
                console.log(response);

                var vote4me_json = jQuery.parseJSON(response);

                // Amaga els botons
                //jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function() {
                //    jQuery(this).find('#vote4me_survey-vote-button').addClass('vote4me_scale_hide');
                //});

                //jQuery(vote4me_item).find('.vote4me_survey-progress-fg').attr('style', 'width:' + vote4me_json.total_vote_percentage + '%');
                //jQuery(vote4me_item).find('.vote4me_survey-progress-label').text(vote4me_json.total_vote_percentage + '%');
                //jQuery(vote4me_item).find('.vote4me_survey-completes').text(vote4me_json.total_opt_vote_count + ' / ' + vote4me_json.total_vote_count);

                setTimeout(function() {
                    jQuery(vote4me_btn).addClass('vote4me_scale_show');
                    jQuery(vote4me_btn).val("Votat!");
                    jQuery(vote4me_btn).toggleClass("vote4me_green_gradient");
                    jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_spinner_stop");
                    jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_drawn");
                }, 300);
            });
        });
    });

    jQuery(this).find('#vote4me_survey-vote-button-final').click(function () {
        var voting_code = jQuery(document).find('#vote4me_voting_code').val();
        console.log("voting_code: " + voting_code);
        if (voting_code == "") {
            alert("Has d'entrar el codi de votació!")
            return;
        }

        var data = {
            'action': 'vote4me_vote',
            'voting_code': voting_code,
            'option_id': -1,
            'poll_id': jQuery(document).find('#vote4me_poll-id').val() // We pass php values differently!
        };

        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(vote4me_ajax_obj.ajax_url, data, function (response) {
            console.log(response);

            var vote4me_json = jQuery.parseJSON(response);

            // Amaga els botons
            jQuery(document).find('.vote4me_survey-item').each(function() {
                jQuery(this).find('#vote4me_survey-vote-button').addClass('vote4me_scale_hide');
            });


            setTimeout(function () {
                jQuery(vote4me_btn).addClass('vote4me_scale_show');
                jQuery(vote4me_btn).val("Votat!");
                jQuery(vote4me_btn).toggleClass("vote4me_green_gradient");
                jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_spinner_stop");
                jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_drawn");
            }, 300);
        });

    });

    jQuery('.vote4me_pop_close').click(function() {
        jQuery('.vote4me_pop_container').fadeOut();
    });

});

// 'voting_code': jQuery(vote4me_item).find('#vote4me_voting_code').val(), 
