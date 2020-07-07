<?php

/**
 * https://github.com/vito/chyrp/blob/35c646dda657300b345a233ab10eaca7ccd4ec10/includes/helpers.php#L515
 * function: sanitize
 * Returns a sanitized string, typically for URLs.
 *
 * Parameters:
 *     $string - The string to sanitize.
 *     $force_lowercase - Force the string to lowercase?
 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
 *     $trunc - Number of characters to truncate to (default 100, 0 to disable).
 */
function sanitize($string, $force_lowercase = false, $anal = false, $trunc = 100) {
    $strip = ["~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
                "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                "—", "–", ",", "<", "..", ">", "/", "?"];
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean);
    $clean = ($trunc ? substr($clean, 0, $trunc) : $clean);
    return $force_lowercase ?
        (function_exists('mb_strtolower') ? mb_strtolower($clean, 'UTF-8') : strtolower($clean)) :
        $clean;
}

function get_string_between($string, $start, $end) {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function stringContains($str, array $arr)
{
    foreach ($arr as $a) {
        if (stripos($str, $a) !== false) {
            return true;
        }
    }

    return false;
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!$%^&*()-=+[]{};#:@~,./<>?', ceil($length / strlen($x)))), 1, $length);
}


/*
 * Script Functions
 */

function checkMainConfig() {
    if (!is_file( dirname(__FILE__).'/config.php' )) {
        echo 'config file missing';
        exit;
    }
}

function printAlerts() {

    if (isset($_SESSION['need_nginx_reload'])) {
        return <<<ALERT
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="dismiss-nginx-reload"><span aria-hidden="true">&times;</span></button>
                <h4 class="alert-heading">Reload Nginx</h4>
                <p>You've changed configuration file(s) or created a new one, so Nginx needs to be reloaded or restarted</p>
            </div>

        </div>
    </div>
</div>
ALERT;
    }

}

function statsBuilder()
{
    $results = [];

    $negativeStatus = ['not found', 'not recognized'];

    /*
     * nginx
     */
    $nginxV = shell_exec('nginx -v 2>&1 | head -n 1');
    $nginxV = is_array($nginxV) ? trim(first($nginxV)) : trim($nginxV);
    $nginxStatus = `systemctl is-active nginx.service`;
    $results['nginx_ver'] = $nginxV;
    $results['nginx_installed'] = ($nginxV && !stringContains($nginxV, $negativeStatus)) ? 1 : 0;
    $results['nginx_line'] = ($nginxV && !stringContains($nginxV, $negativeStatus)) ? 'v'.(end(explode('/', $nginxV))) : '<i class="text-warning">Not installed</i>';
    $results['nginx_status'] = trim($nginxStatus) == 'active' ? 1 : 0;

    /*
     * apache
     */
    $apacheV = shell_exec('httpd -v 2>&1 | head -n 1');
    $apacheV = is_array($apacheV) ? trim(first($apacheV)) : trim($apacheV);
    $apacheStatus = `systemctl is-active httpd.service`;
    $results['apache_ver'] = $apacheV;
    $results['apache_installed'] = ($apacheV && !stringContains($apacheV, $negativeStatus)) ? 1 : 0;
    $results['apache_line'] = ($apacheV && !stringContains($apacheV, $negativeStatus)) ? 'v'.trim(get_string_between($apacheV, '/', '(')) : '<i class="text-warning">Not installed</i>';
    $results['apache_status'] = trim($apacheStatus) == 'active' ? 1 : 0;

    /*
     * postfix
     */
    $postfixV = shell_exec('postconf -d | grep mail_version\ = 2>&1 | head -n 1');
    $postfixV = is_array($postfixV) ? trim(first($postfixV)) : trim($postfixV);
    $postfixStatus = `systemctl is-active postfix.service`;
    $results['postfix_ver'] = $postfixV;
    $results['postfix_installed'] = ($postfixV && !stringContains($postfixV, $negativeStatus)) ? 1 : 0;
    $results['postfix_line'] = ($postfixV && !stringContains($postfixV, $negativeStatus)) ? 'v'.trim(explode('=', $postfixV)[1]) : '<i class="text-warning">Not installed</i>';
    $results['postfix_status'] = trim($postfixStatus) == 'active' ? 1 : 0;

    /*
     * dovecot
     */
    $dovecotV = shell_exec('dovecot --version 2>&1 | head -n 1');
    $dovecotV = is_array($dovecotV) ? trim(first($dovecotV)) : trim($dovecotV);
    $dovecotStatus = `systemctl is-active dovecot.service`;
    $results['dovecot_ver'] = $dovecotV;
    $results['dovecot_installed'] = ($dovecotV && !stringContains($dovecotV, $negativeStatus)) ? 1 : 0;
    $results['dovecot_line'] = ($dovecotV && !stringContains($dovecotV, $negativeStatus)) ? 'v'.trim(explode('(', $dovecotV)[0]) : '<i class="text-warning">Not installed</i>';
    $results['dovecot_status'] = trim($dovecotStatus) == 'active' ? 1 : 0;

    /*
     * vsftpd
     */
    $vsftpdV = shell_exec('vsftpd -v 0>&1 | head -n 1');
    $vsftpdV = is_array($vsftpdV) ? trim(first($vsftpdV)) : trim($vsftpdV);
    $vsftpdStatus = `systemctl is-active vsftpd.service`;
    $results['vsftpd_ver'] = $vsftpdV;
    $results['vsftpd_installed'] = ($vsftpdV && !stringContains($vsftpdV, $negativeStatus)) ? 1 : 0;
    $results['vsftpd_line'] = ($vsftpdV && !stringContains($vsftpdV, $negativeStatus)) ? 'v'.trim(explode('version', $vsftpdV)[1]) : '<i class="text-warning">Not installed</i>';
    $results['vsftpd_status'] = trim($vsftpdStatus) == 'active' ? 1 : 0;

    /*
     * proftpd
     */
    $proftpdV = shell_exec('proftpd --version 2>&1 | head -n 1');
    $proftpdV = is_array($proftpdV) ? trim(first($proftpdV)) : trim($proftpdV);
    $proftpdStatus = `systemctl is-active proftpd.service`;
    $results['proftpd_ver'] = $proftpdV;
    $results['proftpd_installed'] = ($proftpdV && !stringContains($proftpdV, $negativeStatus)) ? 1 : 0;
    $results['proftpd_line'] = ($proftpdV && !stringContains($proftpdV, $negativeStatus)) ? 'v'.trim(explode('Version', $proftpdV)[1]) : '<i class="text-warning">Not installed</i>';
    $results['proftpd_status'] = trim($proftpdStatus) == 'active' ? 1 : 0;

    /*
     * ssh
     */
    $sshV = shell_exec('ssh -V 2>&1 | head -n 1');
    $sshV = is_array($sshV) ? trim(first($sshV)) : trim($sshV);
    $sshStatus = `systemctl is-active ssh.service`;
    $results['ssh_ver'] = $sshV;
    $results['ssh_installed'] = ($sshV && !stringContains($sshV, $negativeStatus)) ? 1 : 0;
    $results['ssh_line'] = ($sshV && !stringContains($sshV, $negativeStatus)) ? 'v'.trim(get_string_between($sshV, 'OpenSSH_', ',')) : '<i class="text-warning">Not installed</i>';
    $results['ssh_status'] = trim($sshStatus) == 'active' ? 1 : 0;

    /*
     * mysql
     */
    $mysqlV = shell_exec('mysql -V 2>&1 | head -n 1');
    $mysqlV = is_array($mysqlV) ? trim(first($mysqlV)) : trim($mysqlV);
    $mysqlStatus = `systemctl is-active mysql.service`;
    $results['mysql_ver'] = $mysqlV;
    $results['mysql_installed'] = ($mysqlV && !stringContains($mysqlV, $negativeStatus)) ? 1 : 0;
    $results['mysql_line'] = ($mysqlV && !stringContains($mysqlV, $negativeStatus)) ? 'v'.trim(get_string_between($mysqlV, 'Distrib', ',')) : '<i class="text-warning">Not installed</i>';
    $results['mysql_status'] = trim($mysqlStatus) == 'active' ? 1 : 0;

    /*
     * PHPs
     */
    foreach (NGM_PHP_VERS as $phpVer) {
        $verConverted = str_replace('.', '', $phpVer);

        $phpV = shell_exec('php'.$phpVer.' -v 2>&1 | head -n 1');
        $phpV = is_array($phpV) ? trim(first($phpV)) : trim($phpV);
        $phpStatus = `systemctl is-active php{$phpVer}-fpm.service`;
        $results['php'.$verConverted.'_ver'] = $phpV;
        $results['php'.$verConverted.'_installed'] = ($phpV && !stringContains($phpV, $negativeStatus)) ? 1 : 0;
        $results['php'.$verConverted.'_line'] = ($phpV && !stringContains($phpV, $negativeStatus)) ? 'v'.trim(get_string_between($phpV, 'PHP ', ' (')) : '<i class="text-warning">Not installed</i>';
        $results['php'.$verConverted.'_status'] = trim($phpStatus) == 'active' ? 1 : 0;
    }

    return $results;
}
