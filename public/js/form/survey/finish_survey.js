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
clearLocalStorage();
//clear local storage value of survey answers.