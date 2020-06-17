<script>
    $(function ($) {
        checkCookie_eu();
        function checkCookie_eu()
        {
            var consent = getCookie_eu("cookiesConsent");
            if (consent == null || consent == "" || consent == undefined)
            {
                // show notification bar
                $('#cookiesConsentContainer').show();
            }
        }

        function setCookie_eu(c_name, value, exdays)
        {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
            document.cookie = c_name + "=" + c_value + "; path=/";

            $('#cookiesConsentContainer').hide('slow');
        }

        function getCookie_eu(c_name)
        {
            var i, x, y, ARRcookies = document.cookie.split(";");
            for (i = 0; i < ARRcookies.length; i++)
            {
                x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x == c_name)
                {
                    return unescape(y);
                }
            }
        }
        $("#cookie_accept .button").click(function (event) {
            event.preventDefault();
            setCookie_eu("cookiesConsent", 1, 30);
        });

    });
</script>

<div id="cookiesConsentContainer" class="container-fluid fixed-bottom" style="display: none">
    <div class="container">
        <div id="cookie_accept">
            <div class="button button-sm button-primary button-winona pull-right"><div class="content-original"><?= e(trans('public.cookiesConsent.accept')) ?></div><div class="content-dubbed"><?= trans('public.cookiesConsent.accept')  ?></div></div>
            <p class="">
                <?= e(trans('public.cookiesConsent.message')) ?>
            </p>
            <br>

        </div>
    </div>
</div>