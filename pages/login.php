<?php

$pageBody = '
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3 col-sm-12">

            <form id="login-form">

                <input type="hidden" name="section" value="check-login">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in-alt"></i> Login</button>
            </form>

        </div>
    </div>
</div>
';





return [
    'pageBody' => $pageBody,
];
