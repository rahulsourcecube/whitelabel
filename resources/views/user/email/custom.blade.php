<?php

use App\Models\SettingModel;
use App\Helpers\Helper;
?>
@if (!empty($company_id))
    <?php $mail = SettingModel::where('user_id', $company_id)->first(); ?>
    @if (!empty($template))
        <?php
        $name = $name;
        $company_title = !empty($mail) && !empty($mail->title) ? $mail->title : 'Referdio';
        
        $company_link = $webUrl ? $webUrl : '';
        $campaign_titles = $campaign_title ? $campaign_title : '';
        $campaign_prices = $campaign_price ? $campaign_price : '';
        // $campaign_join_link = $campaign_join_link ? $campaign_join_link : '';
        
        if (isset($mail) && !empty($mail->logo) && file_exists(base_path() . '/uploads/setting/' . $mail->logo)) {
            $logo = "<img src='" . asset('uploads/setting/' . $mail->logo) . "' style='width: 125px;'>";
        } else {
            $logo = "<img src='" . asset('assets/images/logo/logo.png') . "' style='width: 125px;' alt=''>";
        }
        
        $pattern = '/\[survey\[(.*?)\]\]/';
        preg_match_all($pattern, $template, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $surveyValue) {
                $surveyFrom = Helper::getSurveyFrom($surveyValue);
                $survey_link = $company_link . '/survey' . '/' . $surveyFrom->slug;
        
                $template = str_replace('[survey[' . $surveyValue . ']]', $survey_link, $template);
            }
        }
        
        $html = str_replace(['[user_name]', '[company_logo]', '[company_title]', '[company_web_link]', '[campaign_title]', '[campaign_price]', '[campaign_join_link]'], [$name, $logo, $company_title, $company_link, $campaign_titles, $campaign_prices, $campaign_join_link], $template);
        echo $html;
        ?>
    @endif
@endif
