$(document).on("click", ".SelStudyLine", function() {
    // alert($(this).attr('key_studyline'));
    if ($(this).attr('key_studyline') == 0) {
        $('.PrintNameRoom').attr('href', "StudentsList/Print/" + $(this).attr('key_room') + '/All')
    } else {
        $('.PrintNameRoom').attr('href', "StudentsList/Print/" + $(this).attr('key_room') + '/' + $(this).attr('key_studyline'))
    }

});