<?php
use App\Models\SettingModel;
use App\Models\User;

$userMail = User::where('user_type', '1')->first();
$mail = SettingModel::where('user_id', $userMail->id)->first();

?>
@if (!empty($template))
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
@endif
