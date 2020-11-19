function deleteData(user_number) {
    url = url.replace(':user_number', user_number);
    $("#deleteForm").attr('action', url);
}

function formSubmit() {
    $("#deleteForm").submit();
}