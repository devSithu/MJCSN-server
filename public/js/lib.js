
function array(init) {
    if (!init.remove) {
        Object.defineProperty(init, 'remove', {
            value: function (val) {
                var i = this.indexOf(val);
                return i > -1 ? this.splice(i, 1) : [];
            }
        });
    }
    if (!init.pluck) {
        Object.defineProperty(init, 'pluck', {
            value: function (attr) {
                return array(this.map(function (item) {
                    return item[attr];
                }));
            }
        });
    }
    if (!init.whereIn) {
        Object.defineProperty(init, 'whereIn', {
            value: function (attr, arr) {
                return array(this.filter(function (item) {
                    return arr.indexOf(item[attr]) >= 0;
                }));
            }
        });
    }
    if (!init.whereNotIn) {
        Object.defineProperty(init, 'whereNotIn', {
            value: function (attr, arr) {
                return array(this.filter(function (item) {
                    return arr.indexOf(item[attr]) < 0;
                }));
            }
        });
    }
    if (!init.where) {
        Object.defineProperty(init, 'where', {
            value: function (attr, value) {
                return this.whereIn(attr, [value]);
            }
        });
    }
    if (!init.intersect) {
        Object.defineProperty(init, 'intersect', {
            value: function (arr) {
                return array(this.filter(function (item) {
                    return arr.indexOf(item) >= 0;
                }));
            }
        });
    }
    return init;
}

function insertAtCaret(target, str) {
    var obj = $(target);
    obj.focus();
    if (navigator.userAgent.match(/MSIE/)) {
        var r = document.selection.createRange();
        r.text = str;
        r.select();
    } else {
        var s = obj.val();
        var p = obj.get(0).selectionStart;
        var np = p + str.length;
        obj.val(s.substr(0, p) + str + s.substr(p));
        obj.get(0).setSelectionRange(np, np);
    }
}

function datetime()
{
    var ONE_SECOND = 1000;
    var ONE_MINUTE = 60 * ONE_SECOND;
    var ONE_HOUR = 60 * ONE_MINUTE;
    var ONE_DAY = 24 * ONE_HOUR;

    var dateVar = new (Function.prototype.bind.apply(Date, [null].concat(Array.prototype.slice.call(arguments))));

    dateVar.addDays = function (days) {
        dateVar.setTime(dateVar.getTime() + days * ONE_DAY);
        return dateVar;
    };

    dateVar.daydiff = function ()
    {
        return (dateVar - datetime.apply(null, Array.prototype.slice.call(arguments))) / ONE_DAY;
    }

    dateVar.format = function (format) {
        if (format === "YYYY/MM/DD") {
            return dateVar.getFullYear() + "/" + ('0' + (dateVar.getMonth() + 1)).slice(-2) + "/" + ('0' + dateVar.getDate()).slice(-2);
        }
        return (dateVar.getMonth() + 1) + "月" + dateVar.getDate() + "日";
    }

    dateVar.isDateTime = function () {
        return !isNaN(dateVar.valueOf());
    }

    return dateVar;
}

function random(max, min) {
    min = min === undefined ? 0 : min;
    max = max === undefined ? 1000 : max;

    return Math.floor(Math.random() * (max - min) + min);
}

function alert(msg, options) {
    return bootbox.alert({
        buttons: [],
        size: "small",
//        title: "アラート",
        message: msg,
        backdrop: true,
        className: 'qb-modal qb-modal-alert'
    });
}

function info(title, content, buttons, onEscape, options) {
    onEscape = onEscape === undefined ? true : onEscape;

    return bootbox.dialog({
        title: title,
        onEscape: onEscape,
        message: content,
        closeButton: false,
        buttons: buttons ? (typeof buttons.length === 'undefined' ? [buttons] : buttons) : [],
        backdrop: true,
        className: 'qb-modal'
    });
}

function confirm(title, msg, callback, type, options) {
    options = options ? options : {};

    bootbox.setLocale('ja');
    var opts = {
        title: title,
        callback: callback,
        message: msg,
        className: 'qb-modal',
        backdrop: true,
        closeButton: true,
        buttons: {
            confirm: {
                label: options.confirm ? options.confirm : '確認'
            },
            cancel: {
                label: options.cancel ? options.cancel : 'キャンセル'
            }
        },
    };
    if (type == 'warning') {
        opts.message = '<i class="material-icons qb-warning-icon">error</i>' + '<p class="qb-warning-box">' + msg + '</p>';
    }
    return bootbox.confirm(opts);
}

function prompt(title, inputs, callback, options) {
    options = options ? options : {};

    bootbox.setLocale('ja');
    var form_id = '_prompt_form_' + random();
    var input_id = '_prompt_input_' + random();
    if (inputs === 'email') {
        inputs = '<div class="form-horizontal form-modal-input"><input id="' + input_id + '" maxlength="255" required type="email" class="form-control" placeholder="送信先メールアドレス"></div>';
    } else if (inputs.type === 'select') {
        var s = $('<select id="' + input_id + '" class="form-control" />');
        for (var i in inputs.options) {
            $("<option />", inputs.options[i]).appendTo(s);
        }
        inputs = '<div class="form-horizontal form-modal-input">' + s[0].outerHTML + '</div>';
    } else {
        input_id = null;
    }
    var csrf = token() ? ('<input type="hidden" name="_token" value="' + token() + '">') : '';
    var opts = {
        title: title,
        callback: function (result) {
            if (result) {
                if ($('#' + form_id).valid()) {
                    return callback($('#' + form_id).find(input_id ? '#' + input_id : 'input:not([type=hidden])').val(), $('#' + form_id));
                } else {
                    return false;
                }
            }
        },
        message: '<form method="POST" id="' + form_id + '">' + csrf + inputs + '</form>',
        className: 'qb-modal',
        backdrop: true,
        closeButton: true,
        show: false,
        buttons: {
            confirm: {
                label: options.confirm ? options.confirm : '確認'
            },
            cancel: {
                label: options.cancel ? options.cancel : 'キャンセル'
            }
        },
    };
    var box = bootbox.confirm(opts);
    $('#' + form_id).validate(options.validation ? options.validation : {});
    box.on("keypress", "input[type=text], input[type=password], input[type=email]", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            var preserveDialog = opts.callback(box, event) === false;
            if (!preserveDialog) {
                box.modal("hide");
            }
        }
    });
    box.one("shown.bs.modal", function () {
        $('#' + input_id).focus();
    });
    box.modal("show");
    return box;
}

jQuery.validator.addMethod("email", function (value, element) {
    return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/.test(value);
});

jQuery.validator.addMethod("in", function (value, element) {
    try {
        return JSON.parse($(element).attr('in')).indexOf(value) >= 0;
    } catch (err) {
        return $(element).attr('in').split(',').indexOf(value) >= 0;
    }
}, '正しく入力してください。');

function add_form(action, inputs, attrs) {
    inputs = inputs ? inputs : {};
    attrs = attrs ? attrs : {};

    var form = $('<form>').attr('id', '_form' + random()).attr('method', "POST");
    action ? form.attr("action", action) : '';
    for (var attr in attrs) {
        if (attrs.hasOwnProperty(attr)) {
            form.attr(attr, attrs[attr]);
        }
    }
    if (token()) {
        form.append(jQuery('<input>', {type: 'hidden', name: '_token', value: token()}));
    }
    if (['delete', 'put', 'patch'].indexOf(inputs) >= 0) {
        form.append(jQuery('<input>', {type: 'hidden', name: '_method', value: inputs}));
    } else {
        for (var key in inputs) {
            if (inputs.hasOwnProperty(key)) {
                var values = inputs[key];
                if ($.isArray(values)) {
                    values.forEach(function ( value, index ) {
                        if($.isArray(value)){
                            value.forEach(function (value_children, index_children) {
                                form.append(jQuery('<input>', {type: 'hidden', value: value_children, name: key+'['+index+']['+index_children+']'}));
                            });
                        }else{
                            form.append(jQuery('<input>', {type: 'hidden', value: value, name: key}));
                        }
                    });
                } else {
                    form.append(jQuery('<input>', {type: 'hidden', value: values, name: key}));
                }
            }
        }
    }
    $('body').append(form);
    return form;
}

function token() {
    return $('input:hidden[name=_token]').val();
}

function vars(name, scope) {
    scope = scope ? scope : 'vars';
    if (!$.cps_scopes || !$.cps_scopes[scope]) {
        throw 'Undefined [' + name + '] in [' + scope + '] scope';
    }
    return $.cps_scopes[scope][name];
}

function scope(name) {
    name = name ? name : 'vars';
    if (!$.cps_scopes) {
        throw 'Undefined [' + name + '] scope';
    }
    return $.cps_scopes[name];
}

function parseBool(val) {
    if (val === 'false') {
        return false;
    }
    return val ? true : false;
}

function submit_by_form(action, inputs, attrs) {
    inputs = inputs ? inputs : {};
    attrs = attrs ? attrs : {};

    function createInput(name, value) {
        if (!$.isArray(value)) {
            return '<input type="hidden" name="' + name + '" value="' + value + '">';
        }
        var html = '';
        value.forEach(function (v, i) {
            html += createInput(name + '[' + i + ']', v);
        });
        return html;
    }

    var form = $('<form>', $.extend({id: '_form_' + random(), method: "POST", action: action}, attrs));
    var submit = '';

    if (token()) {
        submit += createInput('_token', token());
    }
    if (['delete', 'put', 'patch'].indexOf(inputs) >= 0) {
        inputs = {_method: inputs};
    }
    $.each(inputs, function (n, v) {
        submit += createInput(n, v);
    });
    $('body').append(form);
    form.html(submit).submit();
    form.remove();
}
