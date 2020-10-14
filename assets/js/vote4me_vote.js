jQuery(document).ready(function() {

    // vote4me_survey-item-id = option_id
    // Votem un candidat
    jQuery('.vote4me_survey-item').each(function() {
        var vote4me_item = jQuery(this);

        jQuery(this).find('#vote4me_survey-vote-button').click(function() {
            // Comprovem que s'hagi entrat un codi de votació
            var voting_code = jQuery(document).find('#vote4me_voting_code').val();
            if (voting_code == "") {
                alert("Has d'entrar el codi de votació!")
                return;
            }

            // Guardem la secretaria votada (per deshabilitar les altres opcions)
            var secretaria_votada = jQuery(vote4me_item).find('#vote4me_secretaria').val();
            console.log("Secretaria votada:" + secretaria_votada);

            // Enviem el vot al servidor (codi de votació, votació, vot)
            var vote4me_btn = jQuery(this);
            // Activem l'animació de l'spinner
            jQuery(vote4me_item).find('.vote4me_spinner').fadeIn();

            var data = {
                'action': 'vote4me_vote',
                'voting_code': voting_code,
                'poll_id': jQuery(vote4me_item).find('#vote4me_poll-id').val(),
                'option_id': jQuery(vote4me_item).find('#vote4me_survey-item-id').val()
            };

            jQuery.post(vote4me_ajax_obj.ajax_url, data, function(response) {
                var vote4me_json = jQuery.parseJSON(response);
                console.log(response);

                if (vote4me_json.voting_status == "error") {
                    alert(vote4m_json.message);
                }
                else {
                    // Deshabilitem les altres opcions de la secretaria votada
                    jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function () {
                        var secretaria = jQuery(this).find('#vote4me_secretaria').val();
                        if (secretaria_votada == secretaria) {
                            var voteBtn = jQuery(this).find('#vote4me_survey-vote-button');
                            voteBtn.val('...');
                            voteBtn.attr('disabled', 'yes');
                        }
                    });

                    // Canviem el botó perquè quedi marcat com a votat i parem l'animació de spinner
                    setTimeout(function () {
                        jQuery(vote4me_btn).addClass('vote4me_scale_show');
                        jQuery(vote4me_btn).val("Votat!");
                        jQuery(vote4me_btn).toggleClass("vote4me_green_gradient");
                        jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_spinner_stop");
                        jQuery(vote4me_item).find('.vote4me_spinner').toggleClass("vote4me_drawn");
                    }, 300);


                    console.log('option_id: ' + vote4m_json.option_id);
                    console.log('votes: ' + vote4me_json.votes);
                    console.log('voting_status: ' + vote4me_json.voting_status);
                }
            });
        });
    });

    // Finalitzem les votacions
    jQuery(this).find('#vote4me_survey-vote-button-final').click(function () {
        // Comprovem que s'hagi entrat el codi de votació
        var voting_code = jQuery(document).find('#vote4me_voting_code').val();      
        if (voting_code == "") {
            alert("Has d'entrar el codi de votació!")
            return;
        }

        // Enviem el vot al servidor (codi de votació, votació, vot)
        // Per indicar que és per finalitzar les votacions, vot = -1
        var data = {
            'action': 'vote4me_vote',
            'voting_code': voting_code,
            'poll_id': jQuery(document).find('#vote4me_poll-id').val(),
            'option_id': -1
        };

        // Enviem la informació al servidor
        jQuery.post(vote4me_ajax_obj.ajax_url, data, function (response) {
            var vote4me_json = jQuery.parseJSON(response);

            // Comprovem la resposta
            if (vote4me_json.voting_status == "error") {
                alert(vote4me_json.message);
            }
            else {
                // Amaga tots els botons de votació
                jQuery(document).find('.vote4me_survey-item').each(function() {
                    jQuery(this).find('#vote4me_survey-vote-button').addClass('vote4me_scale_hide');
                });

                console.log('option_id: ' + vote4m_json.option_id);
                console.log('votes: ' + vote4me_json.votes);
                console.log('total_votes: ' + vote4m_json.total_votes);
                console.log('voting_status: ' + vote4me_json.voting_status);
            }
        });

    });

    jQuery('.vote4me_pop_close').click(function() {
        jQuery('.vote4me_pop_container').fadeOut();
    });

});

// 'voting_code': jQuery(vote4me_item).find('#vote4me_voting_code').val(), 
