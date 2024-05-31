<?php
use App\Models\SettingModel;
$mail = App\Helpers\Helper::getAdminSetting();
$ActivePackageData = App\Helpers\Helper::GetActivePackageData();
if (!empty($company_id)) {
    $mail = SettingModel::where('user_id', $company_id)->first();
}

?>
@if (!empty($template) && $ActivePackageData->mail_temp_status == '1')
    <?php
    $name = $first_name;
    $company_title = !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio';
    $another_tab = ' target="_blank" ';
    $company_link = $webUrl ? $webUrl : '';
    
    if (isset($mail) && !empty($mail->logo) && file_exists(base_path() . '/uploads/setting/' . $mail->logo)) {
        $logo = "<img src='" . asset('uploads/setting/' . $mail->logo) . "' style='width: 125px;'>";
    } else {
        $logo = "<img src='" . asset('assets/images/logo/logo.png') . "' style='width: 125px;' alt=''>";
    }
    
    // Perform the replacement
    $html = str_replace(['[user_name]', '[company_logo]', '[company_title]', '[company_web_link]', '[another_tab]'], [$name, $logo, $company_title, $company_link, $another_tab], $template);
    
    // Output the modified HTML
    echo $html;
    ?>
@else
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio' }}</title>

    </head>

    <body
        style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
        bgcolor="#f6f6f6">


        <table class="body-wrap"
            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;"
            bgcolor="#f6f6f6">
            <tr
                style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                    valign="top"></td>
                <td class="container" width="600"
                    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
                    valign="top">
                    <div class="content"
                        style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope
                            itemtype="http://schema.org/ConfirmAction"
                            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                            <tr
                                style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td class="content-wrap"
                                    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;padding: 30px;border: 3px solid #5d6dc3; display: inline-block; border-radius: 7px; background-color: #fff;"
                                    valign="top">
                                    <meta itemprop="name" content="Confirm Email"
                                        style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                    <table width="100%" cellpadding="0" cellspacing="0"
                                        style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr>
                                            <td style="text-align: center">
                                                <div class="d-none d-md-flex p-h-40">
                                                    @if (isset($mail) && !empty($mail->logo) && file_exists(base_path() . '/uploads/setting/' . $mail->logo))
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
                                            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block"
                                                style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                valign="top">
                                                <p>Dear {{ $first_name }},</p>
                                                <p>We're writing to inform you that your password for has been
                                                    successfully changed.</p>
                                                <p>If you initiated this change, you can disregard this email. However,
                                                    if you did not request this change or believe your account may have
                                                    been compromised.</p>
                                                <p>For security reasons, we recommend reviewing your account activity
                                                    and ensuring that your new password is strong and unique.</p>
                                                <p>Thank you for helping us maintain the security of your account.</p>
                                            </td>
                                        </tr>
                                        <tr
                                            style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block pb-0"
                                                style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                valign="top"><br>
                                                &mdash; <b>Team </b> -
                                                {{ !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;"
                    valign="top"></td>
            </tr>
        </table>
    </body>

    </html>
@endif
