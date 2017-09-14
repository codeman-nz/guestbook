var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var captchaOk = false;

function captchaFilled() {
    captchaOk = true;
}

function captchaExpired() {
    captchaOk = false;
}

$(document).ready(() => {
    var source = $("#entry-template").html();
    var template = Handlebars.compile(source);

    function getPaginator() {
        var Data = {
            currentPage: getUrlParameter('currentPage') || 1
        }
        $.get({
            url: "guestbook-controller.php/get-paginator",
            data: Data,
        }, function(html) {
            $("#Paginator").html(html);
        });
    }

    function getEntries() {
        var currentPage = getUrlParameter('currentPage') || 1;

        $.getJSON(
            "guestbook-controller.php/get-entries?currentPage=" + currentPage,
            function(data) {
                $Entries = $("#Entries");
                $Entries.html('');

                data.forEach(function(entry) {
                    $Entries.append(template(entry));
                });

                getPaginator();
            });
    }

    $("#AddEntryForm").on('submit', (e) => {
        if (!captchaOk) {
            alert('Fill in the capcha!');
            return false;
        }

        e.preventDefault();

        var $Name = $("#Name");
        var $Address = $("#Address");
        var $EmailAddress = $("#EmailAddress");
        var $Message = $("#Message");

        var Data = {
            name: $Name.val().trim(),
            address: $Address.val().trim(),
            emailAddress: $EmailAddress.val().trim(),
            message: $Message.val().trim(),
            captcha: grecaptcha.getResponse()
        };

        $.post({
            url: "guestbook-controller.php/create-entry",
            data: Data,
            success: function() {
                getEntries();

                $Name.val('');
                $Address.val('');
                $EmailAddress.val('');
                $Message.val('');
                grecaptcha.reset();
            }
        });
    });

    getEntries();
});