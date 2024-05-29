$(document).ready(function(){
    $(document).on('change', '.js-select-ib', function(e) {
        e.preventDefault();
        var value = $(this).val();
        if (!value) {
            $('.js-start-hide').hide();
        } else {
            $('.js-first-select').remove();
            $('.js-start-hide').show();
        }
    });
    $(document).on('click', '.js-add-first-level', function(e) {
        e.preventDefault();
        var position = $(".js-first-select").length;
        var iblock = $('.js-select-ib').val();
        var element = $(this);
        $.ajax({
            type: 'POST',
            url: '/local/modules/sdvv.supermenu/ajax/getFirstLevel.php?iblock='+iblock+'&position='+position,
            data: '',
            success: function(data) {
                $(element).parents('tr.js-start-hide').before(data);
            },
            error:  function(xhr, str){
                console.log('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    });
    $(document).on('click', '.js-add-second-level-list', function(e) {
        e.preventDefault();
        var position = $(this).attr('data-position');
        var id = $('select[name="SDVV_SUPERMENU[MENU]['+position+'][ID]"]').val();
        var element = $(this);
        $.ajax({
            type: 'POST',
            url: '/local/modules/sdvv.supermenu/ajax/getSecondLevelList.php?ID='+id+'&position='+position,
            data: '',
            success: function(data) {
                $(element).parents('tr.js-first-select').find('.js-second-level-block').html(data);
                $(element).parents('tr.js-first-select').find('.js-control-level').remove();
            },
            error:  function(xhr, str){
                console.log('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    });
    $(document).on('click', '.js-add-second-level', function(e) {
        e.preventDefault();
        var position = $(this).attr('data-position');
        var id = $('select[name="SDVV_SUPERMENU[MENU]['+position+'][ID]"]').val();
        var positionSecond = $('table[data-length="position_'+position+'"]').length;
        var element = $(this);
        $.ajax({
            type: 'POST',
            url: '/local/modules/sdvv.supermenu/ajax/getSecondLevel.php?ID='+id+'&position='+position+'&positionSecond='+positionSecond,
            data: '',
            success: function(data) {
                $(element).parents('tr.js-first-select').find('.js-second-level-block').append(data);
                $(element).parents('tr.js-first-select').find('.js-hide-second-level').show();
                $(element).parents('tr.js-first-select').find('.js-control-level').remove();
            },
            error:  function(xhr, str){
                console.log('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    });
    $(document).on('click', '.js-add-three-level', function(e) {
        e.preventDefault();
        $(this).hide();
        $(this).parents('.js-thr').find('.js-three-level').show();
    });
});