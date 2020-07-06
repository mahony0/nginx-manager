<?php


$pageBody = '
<div class="container mt-4">';

$pageBody .= '
    <div class="row">
        <div class="col-md-12 text-right">
            <h5>
                <small><a href="javascript:;" id="reload-stat-cache"><i class="fa fa-redo"></i> Recheck Status</a></small> &bull;
                Server Time: <span class="badge badge-info serverTime">--</span> &bull;
                Last update: <span class="badge badge-info lastUpdate">--</span>
            </h5>
        </div>

        <div class="col-md-12">
            <h3>Webserver</h3>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-nginx">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">Nginx</h5>
                            <h6 class="card-subtitle mb-2 text-muted nginx_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse nginx_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link nginx_reload reload-service" data-svcname="nginx"><i class="fa fa-redo"></i> Reload</a>
                            <a href="javascript:;" class="card-link nginx_restart restart-service" data-svcname="nginx"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-apache">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">Apache</h5>
                            <h6 class="card-subtitle mb-2 text-muted apache_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse apache_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link apache_restart restart-service" data-svcname="httpd"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-postfix">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">Postfix</h5>
                            <h6 class="card-subtitle mb-2 text-muted postfix_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse postfix_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link postfix_restart restart-service" data-svcname="postfix"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-dovecot">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">Dovecot</h5>
                            <h6 class="card-subtitle mb-2 text-muted dovecot_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse dovecot_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link dovecot_restart restart-service" data-svcname="dovecot"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-vsftpd">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">vsftpd</h5>
                            <h6 class="card-subtitle mb-2 text-muted vsftpd_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse vsftpd_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link vsftpd_restart restart-service" data-svcname="vsftpd"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-proftpd">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">ProFTPD</h5>
                            <h6 class="card-subtitle mb-2 text-muted proftpd_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse proftpd_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link proftpd_restart restart-service" data-svcname="proftpd"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-ssh">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">SSH</h5>
                            <h6 class="card-subtitle mb-2 text-muted ssh_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse ssh_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link ssh_restart restart-service" data-svcname="ssh"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body" id="status-mysql">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">MySQL</h5>
                            <h6 class="card-subtitle mb-2 text-muted mysql_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse mysql_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link mysql_restart restart-service" data-svcname="mysql"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>';

$pageBody .= '
    <div class="row mt-5">
        <div class="col-md-12">
            <h3>PHP-FPM</h3>
        </div>';

foreach (NGM_PHP_VERS as $phpVer) {
    $verConverted = str_replace('.', '', $phpVer);

    $pageBody .= '
        <div class="col-md-3 mt-3">
            <div class="card">
                <div class="card-body status-php" id="status-php'.$verConverted.'">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">PHP'.($phpVer ? '-'.$phpVer : ' (default)').'</h5>
                            <h6 class="card-subtitle mb-2 text-muted php'.$verConverted.'_line">ver.</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="rounded-pulse medium-pulse php'.$verConverted.'_status passive-server"></div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" class="card-link php'.$verConverted.'_restart restart-service" data-svcname="php'.$phpVer.'-fpm"><i class="fa fa-sync-alt"></i> Restart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}

$pageBody .= '
    </div>';

$pageBody .= '
</div>
';





return [
    'pageBody' => $pageBody,
];
