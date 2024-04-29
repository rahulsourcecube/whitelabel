<?php
use App\Models\SettingModel;
$mail = SettingModel::first();
if (!empty($company_id)) {
    $mail = SettingModel::where('id', $company_id)->first();
}
?>
@if (!empty($template))
    <?php
    $name = $name;
    $company_title = !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio';
    $another_tab = ' target="_blank" ';
    $company_link = $webUrl ? $webUrl : '';
    $submit = route('user.confirmPassword', $token);
    
    if (isset($mail) && !empty($mail->logo) && file_exists(base_path() . '/uploads/setting/' . $mail->logo)) {
        $logo = "<img src='" . asset('uploads/setting/' . $mail->logo) . "' style='width: 125px;'>";
    } else {
        $logo = "<img src='" . asset('assets/images/logo/logo.png') . "' style='width: 125px;' alt=''>";
    }
    
    // Perform the replacement
    $html = str_replace(['[user_name]', '[company_logo]', '[company_title]', '[change_password_link]', "['company_web_link']", '[another_tab]'], [$name, $logo, $company_title, $submit, $company_link, $another_tab], $template);
    
    // Output the modified HTML
    echo $html;
    ?>
@else
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Referdio</title>

    </head>

    <body
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
        bgcolor="#f6f6f6">


        <table class="body-wrap"
            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;"
            bgcolor="#f6f6f6">
            <tr
                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                    valign="top"></td>
                <td class="container" width="600"
                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                    valign="top">
                    <div class="content"
                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope
                            itemtype="http://schema.org/ConfirmAction"
                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                            <tr
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td class="content-wrap"
                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;padding: 30px;border: 3px solid #5d6dc3; display: inline-block; border-radius: 7px; background-color: #fff;"
                                    valign="top">
                                    <meta itemprop="name" content="Confirm Email"
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                    <table width="100%" cellpadding="0" cellspacing="0"
                                        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr>
                                            <td style="text-align: center">
                                                <div class="d-none d-md-flex p-h-40">
                                                    @if (isset($mail) && !empty($mail->logo) && file_exists('uploads/setting/' . $mail->logo))
                                                        <img src="{{ asset('uploads/setting/' . $mail->logo) }}"
                                                            style="width: 125px;">
                                                    @else
                                                        <img src="{{ asset('assets/images/logo/logo.png') }}"
                                                            style="width: 125px;" alt="">
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                valign="top"><br><br>
                                                Hello {{ $name }} ,
                                            </td>
                                        </tr>
                                        <tr
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                valign="top">

                                                You can reset password by clicking below link:<br><br>
                                                <a href="{{ route('user.confirmPassword', $token) }}"
                                                    class="btn-primary" itemprop="url"
                                                    style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background: linear-gradient(to top, #5d6dc3, #3c86d8); margin: 0;  padding: 8px 16px;">Reset
                                                    Password</a>
                                            </td>
                                        </tr>
                                        <tr
                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block pb-0"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                valign="top"><br>
                                                &mdash; <b>Team </b> -
                                                {{ isset($mail->title) ? $mail->title : 'Referdio' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                    valign="top"></td>
            </tr>
        </table>
    </body>

    </html>
@endif
