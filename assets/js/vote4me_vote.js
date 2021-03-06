jQuery(document).ready(function() {

    // vote4me_survey-item-id = option_id
    // Votem un candidat
    jQuery('.vote4me_survey-item').each(function() {
        var vote4me_item = jQuery(this);

        jQuery(this).find('.vote4me_survey-vote-button').click(function() {
            // Comprovem que s'hagi entrat un codi de votació
            var voting_code = jQuery(document).find('#vote4me_voting_code').val();
            if (voting_code == "") {
                alert("Has d'entrar el codi de votació!")
                return;
            }

            console.log("voting code: ", voting_code);

            // Guardem la secretaria votada (per deshabilitar les altres opcions)
            var secretaria_votada = jQuery(vote4me_item).find('.vote4me_secretaria').val();
            //console.log("Secretaria votada:" + secretaria_votada);

            // Enviem el vot al servidor (codi de votació, votació, vot)
            var vote4me_btn = jQuery(this);
            // Activem l'animació de l'spinner
            jQuery(vote4me_item).find('.vote4me_spinner').fadeIn();

            var data = {
                'action': 'vote4me_vote',
                'subaction': 'vote',
                'voting_code': voting_code,
                'poll_id': jQuery(document).find('#vote4me_poll-id').val(),
                'option_id': jQuery(vote4me_item).find('.vote4me_survey-item-id').val()
            };

            jQuery.post(vote4me_ajax_obj.ajax_url, data, function(response) {
                var vote4me_json = jQuery.parseJSON(response);

                if (vote4me_json.voting_status == "error") {
                    alert(vote4me_json.message);
                } else {
                    // Deshabilitem les altres opcions de la secretaria votada
                    jQuery(vote4me_item).parent().find('.vote4me_survey-item').each(function () {
                        var secretaria = jQuery(this).find('.vote4me_secretaria').val();
                        if (secretaria_votada == secretaria) {
                            var voteBtn = jQuery(this).find('.vote4me_survey-vote-button');
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


                    //console.log('option_id: ' + vote4me_json.option_id);
                    console.log('votes: ', vote4me_json.votes);
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

        console.log("voting code: ", voting_code);

        // Enviem el vot al servidor (codi de votació, votació, vot)
        // Per indicar que és per finalitzar les votacions, vot = -1
        var data = {
            'action': 'vote4me_vote',
            'subaction': 'confirmation',
            'voting_code': voting_code,
            'poll_id': jQuery(document).find('#vote4me_poll-id').val()
        };

        // Enviem la informació al servidor
        jQuery.post(vote4me_ajax_obj.ajax_url, data, function (response) {

            console.log("response: ", response);

            var vote4me_json = jQuery.parseJSON(response);

            // Comprovem la resposta
            if (vote4me_json.voting_status == "error") {
                alert(vote4me_json.message);
            } else {
                // Amaga tots els botons de votació
                jQuery(document).find('.vote4me_survey-item').each(function() {
                    jQuery(this).find('.vote4me_survey-vote-button').addClass('vote4me_scale_hide');
                });

                //console.log('option_id: ' + vote4me_json.option_id);
                console.log('votes: ', vote4me_json.votes);
                console.log('total_votes: ' + vote4me_json.total_votes);
                console.log('voting_status: ' + vote4me_json.voting_status);
            }
        });

    });

    // Mostrem els candidats si el codi de votació és correcte
    jQuery(this).find('#vote4me_voting_code_btn').click(function () {
        var voting_code_btn = jQuery(this);

        var voting_code_box = jQuery(document).find('#vote4me_voting_code');

        if (voting_code_box.val() == "") {
            alert("Has d'entrar el codi de votació!")
            return;
        }

        // Enviem el codi de votació al servidor perquè ens digui si
        // és correcte o no
        var data = {
            'action': 'vote4me_vote',
            'subaction': 'check_voting_code',
            'voting_code': voting_code_box.val(),
            'poll_id': jQuery(document).find('#vote4me_poll-id').val(),
        };

        // Enviem la informació al servidor
        jQuery.post(vote4me_ajax_obj.ajax_url, data, function (response) {

            console.log(response);

            var vote4me_json = jQuery.parseJSON(response);

            // Comprovem la resposta
            if (vote4me_json.voting_status == "error") {
                alert(vote4me_json.message);
            }
            else {
                // El codi de votació és correcte
                
                // Deshabilitem l'input box
                voting_code_box.attr('disabled', 'yes');
                
                // Deshabilitem i amaguem el botó de comprovar codi
                voting_code_btn.attr('disabled', 'yes');
                //voting_code_btn.addClass('vote4me_surveys_hide');
                voting_code_btn.attr('style', 'display:none');

                // Mostrem les candidatures
                jQuery('.vote4me_surveys').each(function () {
                    jQuery(this).removeClass('vote4me_surveys_hide');
                });
            }
        });
    });

    jQuery('.vote4me_pop_close').click(function() {
        jQuery('.vote4me_pop_container').fadeOut();
    });

});

// 'voting_code': jQuery(vote4me_item).find('#vote4me_voting_code').val(), 
