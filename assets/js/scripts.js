function swalResponse(data, refreshPage, redirectTo) {
    var refreshStatus = false;
    if (refreshPage) {
        refreshStatus = refreshPage;
    }

    var redirectStatus = false;
    if (redirectTo) {
        redirectStatus = redirectTo;
    }

    if (data.status === 1) {
        Swal.fire({
            type: "success",
            title: data.title ? data.title : "Successful",
            text: data.msg,
            confirmButtonColor: "#0f9aee",
            confirmButtonText: "Close"
        }).then(function(result) {
            if (refreshStatus) {
                location.reload();
            }

            if (redirectStatus) {
                window.location.href = redirectStatus;
            }
        });
    } else {
        Swal.fire({
            type: "error",
            title: data.title ? data.title : "Oops",
            text: data.msg,
            confirmButtonColor: "#0f9aee",
            confirmButtonText: "Close"
        }).then(function(result) {
            //
        });
    }
}

function getStatsForHome(force) {
    let sect = force ? "recheck-home-stats" : "get-home-stats";

    jQuery(function($) {
        $("#status-nginx").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-apache").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-postfix").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-dovecot").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-vsftpd").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-proftpd").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-ssh").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $("#status-mysql").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });
        $(".status-php").LoadingOverlay("show", { background  : "rgba(255, 255, 255, 0.8)" });

        $.post("/ajax.php", {section: sect}, function(response) {
            console.log(response);

            if (response.status === 1) {
                $(".serverTime").text(response.serverTime);
                $(".lastUpdate").text(response.lastUpdate);

                if (!response.results.nginx_installed) { $(".nginx_reload").hide(); $(".nginx_restart").hide(); }
                if (response.results.nginx_line) { $(".nginx_line").html(response.results.nginx_line); }
                if (response.results.nginx_status === 1) { $(".nginx_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-nginx").LoadingOverlay("hide");

                if (!response.results.apache_installed) { $(".apache_restart").hide(); }
                if (response.results.apache_line) { $(".apache_line").html(response.results.apache_line); }
                if (response.results.apache_status === 1) { $(".apache_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-apache").LoadingOverlay("hide");

                if (!response.results.postfix_installed) { $(".postfix_restart").hide(); }
                if (response.results.postfix_line) { $(".postfix_line").html(response.results.postfix_line); }
                if (response.results.postfix_status === 1) { $(".postfix_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-postfix").LoadingOverlay("hide");

                if (!response.results.dovecot_installed) { $(".dovecot_restart").hide(); }
                if (response.results.dovecot_line) { $(".dovecot_line").html(response.results.dovecot_line); }
                if (response.results.dovecot_status === 1) { $(".dovecot_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-dovecot").LoadingOverlay("hide");

                if (!response.results.vsftpd_installed) { $(".vsftpd_restart").hide(); }
                if (response.results.vsftpd_line) { $(".vsftpd_line").html(response.results.vsftpd_line); }
                if (response.results.vsftpd_status === 1) { $(".vsftpd_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-vsftpd").LoadingOverlay("hide");

                if (!response.results.proftpd_installed) { $(".proftpd_restart").hide(); }
                if (response.results.proftpd_line) { $(".proftpd_line").html(response.results.proftpd_line); }
                if (response.results.proftpd_status === 1) { $(".proftpd_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-proftpd").LoadingOverlay("hide");

                if (!response.results.ssh_installed) { $(".ssh_restart").hide(); }
                if (response.results.ssh_line) { $(".ssh_line").html(response.results.ssh_line); }
                if (response.results.ssh_status === 1) { $(".ssh_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-ssh").LoadingOverlay("hide");

                if (!response.results.mysql_installed) { $(".mysql_restart").hide(); }
                if (response.results.mysql_line) { $(".mysql_line").html(response.results.mysql_line); }
                if (response.results.mysql_status === 1) { $(".mysql_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-mysql").LoadingOverlay("hide");

                //

                if (!response.results.php_installed) { $(".php_restart").hide(); }
                if (response.results.php_line) { $(".php_line").html(response.results.php_line); }
                if (response.results.php_status === 1) { $(".php_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php").LoadingOverlay("hide");

                if (!response.results.php56_installed) { $(".php56_restart").hide(); }
                if (response.results.php56_line) { $(".php56_line").html(response.results.php56_line); }
                if (response.results.php56_status === 1) { $(".php56_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php56").LoadingOverlay("hide");

                if (!response.results.php70_installed) { $(".php70_restart").hide(); }
                if (response.results.php70_line) { $(".php70_line").html(response.results.php70_line); }
                if (response.results.php70_status === 1) { $(".php70_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php70").LoadingOverlay("hide");

                if (!response.results.php71_installed) { $(".php71_restart").hide(); }
                if (response.results.php71_line) { $(".php71_line").html(response.results.php71_line); }
                if (response.results.php71_status === 1) { $(".php71_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php71").LoadingOverlay("hide");

                if (!response.results.php72_installed) { $(".php72_restart").hide(); }
                if (response.results.php72_line) { $(".php72_line").html(response.results.php72_line); }
                if (response.results.php72_status === 1) { $(".php72_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php72").LoadingOverlay("hide");

                if (!response.results.php73_installed) { $(".php73_restart").hide(); }
                if (response.results.php73_line) { $(".php73_line").html(response.results.php73_line); }
                if (response.results.php73_status === 1) { $(".php73_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php73").LoadingOverlay("hide");

                if (!response.results.php74_installed) { $(".php74_restart").hide(); }
                if (response.results.php74_line) { $(".php74_line").html(response.results.php74_line); }
                if (response.results.php74_status === 1) { $(".php74_status").removeClass("passive-server").addClass("active-server"); }
                $("#status-php74").LoadingOverlay("hide");
            }
        });
    });
}



/*
 * Pages
 */
jQuery(function($) {
    let activePageSlug = $('meta[name=pageslug]').attr("content");

    // disable form element caching
    $("form :input").attr("autocomplete", "off");

    $("#dismiss-nginx-reload").click(function() {
        $.post("/ajax.php", {section: 'dismiss-nginx-reload'}, function(response) {
            // swalResponse(response);
        });
    });

    $("#logout-btn").click(function() {
        $.post("/ajax.php", {section: 'logout'}, function(response) {
            swalResponse(response, false, "/");
        });
    });

    if (activePageSlug === "login") {
        // login page

        $("#login-form").submit(function(e) {
            e.preventDefault();

            $.post("/ajax.php", $(this).serialize(), function(response) {
                swalResponse(response, 1);
            });

            return false;
        });

    } else if (activePageSlug === "home") {
        // homepage

        getStatsForHome();

        $("#reload-stat-cache").click(function() {
            getStatsForHome(true);
        });

        $(".reload-service").click(function() {
            let svcname = $(this).data("svcname");

            Swal.fire({
                title: 'Reload "' + svcname + '" Service',
                text: "Are you sure you want to reload this service?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reload it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", {section: 'reload-service', svcname: svcname}, function(response) {
                        swalResponse(response, 1);
                    });
                }
            })
        });

        $(".restart-service").click(function() {
            let svcname = $(this).data("svcname");

            Swal.fire({
                title: 'Restart "' + svcname + '" Service',
                text: "Are you sure you want to restart this service?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, restart it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", {section: 'restart-service', svcname: svcname}, function(response) {
                        swalResponse(response, 1);
                    });
                }
            })
        });

    } else if (activePageSlug === "domains") {
        // domains page

        $("#disable-domain").click(function() {
            let fname = $(this).data("fname");

            Swal.fire({
                title: 'Disable Domain',
                text: "Are you sure you want to disable this domain?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, disable it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", {section: 'disable-domain', fname: fname}, function(response) {
                        swalResponse(response, false, "/?page=domains");
                    });
                }
            })
        });

        $("#enable-domain").click(function() {
            let fname = $(this).data("fname");

            Swal.fire({
                title: 'Enable Domain',
                text: "Are you sure you want to enable this domain?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, enable it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", {section: 'enable-domain', fname: fname}, function(response) {
                        swalResponse(response, false, "/?page=domains");
                    });
                }
            })
        });

        $("#domain-content-form").submit(function(e) {
            e.preventDefault();

            let fname = $("#content-form-fname").val();
            let fileName = $("#content-form-filename").val();

            Swal.fire({
                title: 'Change ' + fileName,
                text: "Are you sure you want to change content of the file?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", $(this).serialize(), function(response) {
                        swalResponse(response, 1);
                    });
                }
            });

            return false;
        });

        $("#create-domain-form").submit(function(e) {
            e.preventDefault();

            $.post("/ajax.php", $(this).serialize(), function(response) {
                swalResponse(response, false, response.redirectTo);
            });

            return false;
        });

    } else if (activePageSlug === "templates") {
        // templates page

        $("#delete-template").click(function() {
            let fname = $(this).data("fname");

            Swal.fire({
                title: 'Delete Template',
                text: "Are you sure you want to delete this template?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", {section: 'delete-template', fname: fname}, function(response) {
                        swalResponse(response, false, "/?page=templates");
                    });
                }
            })
        });

        $("#template-content-form").submit(function(e) {
            e.preventDefault();

            let fname = $("#content-form-fname").val();
            let fileName = $("#content-form-filename").val();

            Swal.fire({
                title: 'Change ' + fileName,
                text: "Are you sure you want to change content of this template file?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", $(this).serialize(), function(response) {
                        swalResponse(response, 1);
                    });
                }
            });

            return false;
        });

        $("#template-new-form").submit(function(e) {
            e.preventDefault();

            $.post("/ajax.php", $(this).serialize(), function(response) {
                swalResponse(response, 1);
            });

            return false;
        });
    } else if (activePageSlug === "main-configs") {
        // main-configs page

        $("#main-config-content-form").submit(function(e) {
            e.preventDefault();

            let fname = $("#content-form-fname").val();
            let fileName = $("#content-form-filename").val();

            Swal.fire({
                title: 'Change ' + fileName,
                text: "Are you sure you want to change content of this config file?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.value) {
                    $.post("/ajax.php", $(this).serialize(), function(response) {
                        swalResponse(response, 1);
                    });
                }
            });

            return false;
        });
    }

});
