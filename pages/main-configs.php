<?php


$directory = null;
$fileCount = 0;

$confFiles = array_filter(glob(NGM_CONFDIR_MAIN.'*'), 'is_file');
foreach ($confFiles as $confFile) {
    $pathAndFullFilename = preg_split('/\/(?!.*\/)/', $confFile);

    $directory[] = [
        'base64' => base64_encode($pathAndFullFilename[1]),
        'onlyFilename' => $pathAndFullFilename[1]
    ];
    $fileCount++;
}

$pageBody = '
<div class="container mt-4">
    <div class="row">';

    $pageBody .= '
    <div class="col-md-3">
        <h1 class="mb-4">Configs ('.$fileCount.')</h1>
        <div class="list-group main-configs-list">';

            foreach ($directory as $confFile) {
                $pageBody .= '<a href="/?page=main-configs&fname='.$confFile['base64'].'" class="list-group-item text-info">
                    <span><i class="fa fa-file-code"></i> '.$confFile['onlyFilename'].' <span class="float-right"><i class="fa fa-angle-double-right"></i></span></span>
                </a>';
            }

            $pageBody .= '
        </div>
    </div>';

    if (isset($_GET['fname'])) {

        $filename = base64_decode($_GET['fname']);
        $filename = sanitize($filename);
        $filePath = NGM_CONFDIR_MAIN.$filename;

        if (!is_file($filePath)) {
            header('Location: /main-configs');
            exit;
        }

        $pathAndFullFilename = preg_split('/\/(?!.*\/)/', $filePath);

        $fileContent = file_get_contents($filePath);

        $pageBody .= '
            <div class="col-md-9">
                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        <h4>
                            <span>'.$filename.'</span>
                        </h4>
                        <h6>'.$filePath.'</h6>
                    </div>

                    <div class="card-body">
                        <form id="main-config-content-form" accept-charset="UTF-8">

                            <input type="hidden" name="section" value="change-main-config-content">
                            <input type="hidden" name="fname" id="content-form-fname" value="'.$_GET['fname'].'">
                            <input type="hidden" id="content-form-filename" value="'.$pathAndFullFilename[1].'">

                            <div class="form-group">
                                <label for="main_config_content">File Content</label>
                                <textarea class="form-control" id="main_config_content" name="main_config_content" rows="20">'.$fileContent.'</textarea>
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
            <div class="col-md-9">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h1 class="display-4">Select Configuration File</h1>
                        <p class="lead">Select a file from the list for editing the source code</p>
                    </div>
                </div>
            </div>';

    }

$pageBody .= '
    </div>
</div>
';





return [
    'pageBody' => $pageBody,
];
