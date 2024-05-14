<?php

use App\Models\SettingModel;
$mail = SettingModel::first();
if (!empty($company_id)) {
    $mail = SettingModel::where('user_id', $company_id)->first();
}
?>

@if (!empty($template))
    <?php
    $name = $name;
    $company_title = !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio';
    
    $company_link = $webUrl ? $webUrl : '';
    $campaign_titles = $campaign_title ? $campaign_title : '';
    $campaign_prices = $campaign_price ? $campaign_price : '';
    
    if (isset($mail) && !empty($mail->logo) && file_exists(base_path() . '/uploads/setting/' . $mail->logo)) {
        $logo = "<img src='" . asset('uploads/setting/' . $mail->logo) . "' style='width: 125px;'>";
    } else {
        $logo = "<img src='" . asset('assets/images/logo/logo.png') . "' style='width: 125px;' alt=''>";
    }
    
    // Perform the replacement
    $html = str_replace(['[user_name]', '[company_logo]', '[company_title]', '[company_web_link]', '[campaign_title]', '[campaign_price]'], [$name, $logo, $company_title, $company_link, $campaign_titles, $campaign_prices], $template);
    // Output the modified HTML
    
    echo $html;
    ?>
@endif
