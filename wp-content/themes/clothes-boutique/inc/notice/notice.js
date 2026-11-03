(function ($) {
    "use strict";

    $("#install-activate-button").on("click", function(e){
        e.preventDefault();
        var button = $(this);
        button.prop("disabled", true).text("Installing & Activating Plugins...").addClass("processing-spinner");

        $.post(clothes_boutique_localize.ajax_url, {
            action: "clothes_boutique_install_and_activate_plugins",
            nonce: clothes_boutique_localize.nonce
        })
        .done(function(response){
            if(response.success){
                button.text("Importing Demo Data...");

                $.post(clothes_boutique_localize.ajax_url, {
                    action: "clothes_boutique_run_demo_import",
                    nonce: clothes_boutique_localize.demo_nonce
                })
                .done(function(importResponse){
                    if(importResponse.success){
                        button.text("Demo Imported Successfully");
                        window.location.href = clothes_boutique_localize.redirect_url;
                    } else {
                        button.prop("disabled", false).removeClass("processing-spinner").text("Import Failed");
                        console.log(importResponse);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    button.prop("disabled", false).removeClass("processing-spinner").text("Import AJAX Error");
                    console.log('Import AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                });

            } else {
                button.prop("disabled", false).removeClass("processing-spinner").text("Plugin Installation Failed");
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            button.prop("disabled", false).removeClass("processing-spinner").text("AJAX Error");
            console.log('Plugin Install AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
        });
    });

    // Handle notice dismiss button click
    $(document).on('click', '.notice-info .notice-dismiss', function () {
        var type = $(this).closest('.notice-info').data('notice');

        $.ajax({
            type: 'POST',
            url: clothes_boutique_localize.ajax_url,
            data: {
                action: 'clothes_boutique_dismissed_notice_handler',
                type: type,
                wpnonce: clothes_boutique_localize.dismiss_nonce
            },
            success: function (response) {
                if (response.success) {
                    console.log("Notice dismissed successfully");
                } else {
                    console.log("Failed to dismiss notice");
                }
            }
        });
    });

})(jQuery)
