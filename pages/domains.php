<?php


$directory = null;
$fileCount = 0;

if (NGM_CONF_STYLE == 'source') {
    $confFiles = array_filter(glob(NGM_CONFDIR_SOURCE.'*'), 'is_file');
} elseif (NGM_CONF_STYLE == 'apache') {
    $confFiles = array_filter(glob(NGM_CONFDIR_AVAILABLE.'*'), 'is_file');
}

foreach ($confFiles as $confFile) {
    $pathAndFullFilename = preg_split('/\/(?!.*\/)/', $confFile);
    $filenameAndExtension = preg_split('/\.(?!.*\.)/', $pathAndFullFilename[1]);

    $directory[] = [
        'filePath' => $confFile,
        'base64' => base64_encode($pathAndFullFilename[1]),
        'onlyFilename' => $filenameAndExtension[0],
        'status' => $filenameAndExtension[1]
    ];
    $fileCount++;
}


// templates
$templateFilesForOptions = '';
$templatesOnlyFiles = array_filter(glob(realpath('templates').'/*'), 'is_file');

foreach ($templatesOnlyFiles as $templateFile) {
    $pathAndFullFilename = preg_split('/\/(?!.*\/)/', $templateFile);

    $templateFilesForOptions .= '<option value="'.$pathAndFullFilename[1].'">'.$pathAndFullFilename[1].'</option>';
}



$pageBody = '
<div class="container mt-4">
    <div class="row">';


if (isset($_GET['fname'])) {

    $filename = base64_decode($_GET['fname']);
    $filename = sanitize($filename);
    if (NGM_CONF_STYLE == 'source') {
        $filePath = NGM_CONFDIR_SOURCE.$filename;
    } elseif (NGM_CONF_STYLE == 'apache') {
        $filePath = NGM_CONFDIR_AVAILABLE.$filename;
    }

    if (!is_file($filePath)) {
        header('Location: /domains');
        exit;
    }

    $filenameAndExtension = preg_split('/\.(?!.*\.)/', $filename);

    $fileContent = file_get_contents($filePath);

    $pageBody .= '
        <div class="col-md-3">
            <h1 class="mb-4">Domains ('.$fileCount.')</h1>
            <div class="list-group domains-list">';

                foreach ($directory as $file) {
                    $pageBody .= '<a href="/?page=domains&fname='.$file['base64'].'" class="list-group-item text-info">
                        <div class="rounded-pulse '.($file['status'] == 'conf' ? 'active' : 'passive').'-server"></div>
                        <span>'.$file['onlyFilename'].' <span class="float-right"><i class="fa fa-angle-double-right"></i></span></span>
                    </a>';
                }

                $pageBody .= '
            </div>
        </div>';

    $pageBody .= '
        <div class="col-md-9">
            <div class="card card-outline-secondary my-4">
                <div class="card-header">
                    <h4>
                        <span>'.$filename.'</span>

                        <div class="btn-group mr-2 float-right '.($filenameAndExtension[1] == 'conf' ? '' : 'd-none').'" role="group" aria-label="Disable">
                            <a href="javascript:;"
                                id="disable-domain"
                                data-fname="'.$_GET['fname'].'"
                                class="btn btn-warning"><i class="fa fa-times-circle"></i> Disable
                            </a>
                        </div>
                        <div class="btn-group mr-2 float-right '.($filenameAndExtension[1] == 'conf' ? 'd-none' : '').'" role="group" aria-label="Enable">
                            <a href="javascript:;"
                                id="enable-domain"
                                data-fname="'.$_GET['fname'].'"
                                class="btn btn-info"><i class="fa fa-check-square"></i> Enable
                            </a>
                        </div>
                    </h4>
                    <h6>'.$filePath.'</h6>
                </div>

                <div class="card-body">
                    <form id="domain-content-form" accept-charset="UTF-8">

                        <input type="hidden" name="section" value="change-conf-content">
                        <input type="hidden" name="fname" id="content-form-fname" value="'.$_GET['fname'].'">
                        <input type="hidden" id="content-form-filename" value="'.$filename.'">

                        <div class="form-group">
                            <label for="conf_content">File Content</label>
                            <textarea class="form-control" id="conf_content" name="conf_content" rows="20">'.$fileContent.'</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';

} else {

    $pageBody .= '
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#newDomainModal"><i class="fa fa-plus"></i> Add New Domain</button>
        </div>

        <div class="col-md-12 mt-3">';

    foreach (array_chunk($directory, 3) as $fileGroup) {
        $pageBody .= '
            <div class="row">';

        foreach ($fileGroup as $file) {
            $filePath = $file['filePath'];
            $fileLastUpdate = `stat -c '%y' {$filePath}`;
            $fileLastUpdateParsed = preg_split('/\.(?!.*\.)/', $fileLastUpdate);

            $pageBody .= '
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title">
                                <a href="/?page=domains&fname='.$file['base64'].'" class="text-dark">'.$file['onlyFilename'].'</a>
                            </h4>
                            <p class="card-text text-muted">Updated: '.$fileLastUpdateParsed[0].' +'.substr(strstr($fileLastUpdateParsed[1], '+'), 1).'</p>
                        </div>
                        <div class="card-footer">
                            <h5><span class="badge badge-'.($file['status'] == 'conf' ? 'info' : 'warning').'">'.($file['status'] == 'conf' ? 'Active' : 'Disabled').'</span></h5>

                            <a href="/?page=domains&fname='.$file['base64'].'" class="btn btn-info float-right"><i class="far fa-file-code"></i> Edit File</a>
                        </div>
                    </div>
                </div>';
        }

        $pageBody .= '
            </div>';
    }

    $pageBody .= '
        </div>';

}

$pageBody .= '
    </div>
</div>';


// new domain modal
$pageBody .= '
<div class="modal fade" id="newDomainModal" tabindex="-1" role="dialog" aria-labelledby="newDomainModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form id="create-domain-form">

                <input type="hidden" name="section" value="create-domain">

                <div class="modal-header">
                    <h5 class="modal-title" id="newDomainModalLabel">Create Domain Config File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_domain_name">Domain Name *</label>
                        <input type="text" class="form-control" id="new_domain_name" name="new_domain_name" placeholder="domain.tld" required>
                    </div>

                    <div class="form-group">
                        <label for="new_domain_template">Domain Template File</label>
                        <select class="form-control" id="new_domain_template" name="new_domain_template">
                            <option value="">** Empty</option>
                            '.$templateFilesForOptions.'
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Create &amp; Continue Editing</button>
                </div>
            </form>

        </div>
    </div>
</div>
';




return [
    'pageBody' => $pageBody,
];
