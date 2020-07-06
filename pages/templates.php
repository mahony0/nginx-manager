<?php


$directory = null;
$fileCount = 0;

$templateFiles = array_filter(glob(realpath('templates').'/*'), 'is_file');
foreach ($templateFiles as $templateFile) {
    $pathAndFullFilename = preg_split('/\/(?!.*\/)/', $templateFile);

    $directory[] = [
        'base64' => base64_encode($pathAndFullFilename[1]),
        'onlyFilename' => $pathAndFullFilename[1]
    ];
    $fileCount++;
}

$pageBody = '
<div class="container mt-4">
    <div class="row">';

    if (!isset($_GET['fname'])) {
        $pageBody .= '
            <div class="col-md-12 text-right mb-4">
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#newTemplateModal"><i class="fa fa-plus"></i> Create Template</button>
            </div>';
    }

    $pageBody .= '
    <div class="col-md-3">
        <h1 class="mb-4">Templates ('.$fileCount.')</h1>
        <div class="list-group templates-list">';

            foreach ($directory as $templateFile) {
                $pageBody .= '<a href="/?page=templates&fname='.$templateFile['base64'].'" class="list-group-item text-info">
                    <span><i class="fa fa-file-code"></i> '.$templateFile['onlyFilename'].' <span class="float-right"><i class="fa fa-angle-double-right"></i></span></span>
                </a>';
            }

            $pageBody .= '
        </div>
    </div>';

    if (isset($_GET['fname'])) {

        $filename = base64_decode($_GET['fname']);
        $filename = sanitize($filename);
        $filePath = realpath('templates').'/'.$filename;

        if (!is_file($filePath)) {
            header('Location: /templates');
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

                            <div class="btn-group mr-2 float-right" role="group" aria-label="Delete">
                                <a href="javascript:;"
                                    id="delete-template"
                                    data-fname="'.$_GET['fname'].'"
                                    class="btn btn-warning"><i class="fa fa-trash-alt"></i> Delete
                                </a>
                            </div>
                        </h4>
                        <h6>'.$filePath.'</h6>
                    </div>

                    <div class="card-body">
                        <form id="template-content-form" accept-charset="UTF-8">

                            <input type="hidden" name="section" value="change-template-content">
                            <input type="hidden" name="fname" id="content-form-fname" value="'.$_GET['fname'].'">
                            <input type="hidden" id="content-form-filename" value="'.$pathAndFullFilename[1].'">

                            <div class="form-group">
                                <label for="template_content">File Content</label>
                                <textarea class="form-control" id="template_content" name="template_content" rows="20">'.$fileContent.'</textarea>
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
                        <h1 class="display-4">Select Template</h1>
                        <p class="lead">Select a template from the list for editing the source code</p>
                    </div>
                </div>
            </div>';

    }

$pageBody .= '
    </div>
</div>
';


// new template modal
$pageBody .= '
<div class="modal fade" id="newTemplateModal" tabindex="-1" role="dialog" aria-labelledby="newTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form id="template-new-form">

                <input type="hidden" name="section" value="create-template">

                <div class="modal-header">
                    <h5 class="modal-title" id="newTemplateModalLabel">Create Template Stub</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_template_name">Template File Name *</label>
                        <input type="text" class="form-control" id="new_template_name" name="new_template_name" placeholder="sample" required>
                    </div>

                    <div class="form-group">
                        <label for="new_template_content">Template Content *</label>
                        <textarea class="form-control" id="new_template_content" name="new_template_content" rows="20" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Create</button>
                </div>
            </form>

        </div>
    </div>
</div>
';





return [
    'pageBody' => $pageBody,
];
