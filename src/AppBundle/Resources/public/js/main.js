$.fn.serializeObject = function() {

    var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };


    this.build = function(base, key, value) {
        base[key] = value;
        return base;
    };

    this.push_counter = function(key) {
        if (push_counters[key] === undefined) {
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };

    $.each($(this).serializeArray(), function() {

        // skip invalid keys
        if (!patterns.validate.test(this.name)) {
            return;
        }

        var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

        while ((k = keys.pop()) !== undefined) {

            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

            // push
            if (k.match(patterns.push)) {
                merge = self.build([], self.push_counter(reverse_key), merge);
            }

            // fixed
            else if (k.match(patterns.fixed)) {
                merge = self.build([], k, merge);
            }

            // named
            else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
            }
        }

        json = $.extend(true, json, merge);
    });

    return json;
};

// implement JSON.stringify serialization
JSON.stringify = JSON.stringify || function(obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string")
            obj = '"' + obj + '"';
        return String(obj);
    }
    else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n];
            t = typeof (v);
            if (t == "string")
                v = '"' + v + '"';
            else if (t == "object" && v !== null)
                v = JSON.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};

function jgrowl(mensaje, tipo)
{
    $.jGrowl(mensaje, {
        'titulo': "Mensaje",
        'estilo': this.color + " " + tipo
    });
}

function toFloat(num) {
    if ($.isNumeric(num))
        return formatear(num);
    if (!num)
        return 0;
    num = parseFloat(num.replace(/[^\d|\.]/g, ''));
    return isNaN(num) ? 0 : formatear(num);
}

function formatear(num) {
    num = num * 100;
    num = parseInt(num) / 100.0;
    return num;
}

var tmp = {one: 1, two: "2"};

var contentRes = null;

$("body").on('click', "a.ajax", function(event) {
    event.preventDefault()
    $($(this).data('res')).load($(this).attr("href"));
})
$("body").on('submit', "form.ajax", function(event) {
    event.preventDefault()
    window.contentRes = $($(this).data("res"))
    $.ajax({
        url: $(this).attr("action"),
        data: JSON.stringify($(this).serializeObject()),
        type: $(this).attr("method"),
        dataType: 'jsonp',
        jsonpCallback: 'alertMessage',
        contentType: "application/json",
        success: function(response) {
        }
    })
})

function alertMessage(messages)
{
    console.log(messages)
}

function jgrowlSuccess(message)
{
    jgrowl(message, 'info');
    $("*").modal("hide");
}

function alertFormError(messages)
{
    var alert = "<div class=\"alert alert-error\">\n\
     <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n\
     <%= message %></div>";
    window.contentRes.html("")
    $.each(messages, function() {
        window.contentRes.append(_.template(alert, this))
    })
}

function redirect(url) {
    window.location = url
}

$(document).ajaxStart(function() {
    $(".loading").show(0)
}).ajaxComplete(function() {
    $(".loading").hide(0)
})

/**
 * 
 * @param {type} url
 * @param {type} params
 * @param {type} before
 * @returns {$.fn@call;each}
 */
$.fn.loadSelect = function(url, params, before) {

    return this.each(function() {
        var select = $(this).html('');
        var option = _.template('<option value="<%= value %>"><%= label %></option>');
        
        $.getJSON(url, params, function(json){
            if(_.isFunction(before)){
                json = before(json);
            }
            _.forEach(json, function(label, value){
                select.append(option({label: label, value: value}));
            });
        });
    });
    
};