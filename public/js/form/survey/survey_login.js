// $('.name-control').on('change', function () {
//     var $wrapper = $(this).closest('._wrapper');console.log($wrapper.find('.fullname'));
//     var $first = $wrapper.find('.name-first');
//     $wrapper.find('.fullname').val($first.val().trim());
// }).trigger('change');

$(document).ready(function() {
    var sPageURL = window.location.search.substring(1);
    var sParameterName = sPageURL.split('=');
    if (sParameterName[0] === 'rnd_id') {
        return $(".qb-answer-survey-account").text("※Noと氏名をご確認の上、回答にお進み下さい");
    }
});

$('.btn-confirm').on('click', function() {
    clearLocalStorage();
    //clear local storage value of survey answers.
});

function clearLocalStorage() {
    var local_storage_arr = []; // Array to hold the keys
    for (var i = 0; i < localStorage.length; i++){
        if (localStorage.key(i).substring(0, 16) == 'answer_question_') {
            local_storage_arr.push(localStorage.key(i));
        }
    }

    for (var i = 0; i < local_storage_arr.length; i++) {
        localStorage.removeItem(local_storage_arr[i]);
    }
}