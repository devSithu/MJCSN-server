$(function($) {
    $(document).ready(function() {
        $.datetimepicker.setLocale('en');
        $('.app-date-picker').datetimepicker({
            lang: 'en',
            timepicker: false,
            mask: true,
            format: 'Y/m/d'
        });

        let fromDate = $("input[name=fromDate]").val();
        let toDate = $("input[name=toDate]").val();
        if (isNaN(Date.parse(fromDate))) {
            $("input[name=fromDate]").val('')
        }
        if (isNaN(Date.parse(toDate))) {
            $("input[name=toDate]").val('')
        }

        let registerFromDate = $("input[name=registerFromDate]").val();
        let registerToDate = $("input[name=registerToDate]").val();
        if (isNaN(Date.parse(registerFromDate))) {
            $("input[name=registerFromDate]").val('')
        }
        if (isNaN(Date.parse(registerToDate))) {
            $("input[name=registerToDate]").val('')
        }
    });
});