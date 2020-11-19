function deleteData(user_id) {
    var id = user_id;
    url = url.replace(':user_id', id);
    $("#deleteForm").attr('action', url);
}

function formSubmit() {
    $("#deleteForm").submit();
}
