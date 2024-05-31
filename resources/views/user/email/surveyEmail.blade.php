<?php

use App\Models\SettingModel;
use App\Helpers\Helper;
$ActivePackageData = App\Helpers\Helper::GetActivePackageData();

?>

<?php $mail = SettingModel::where('user_id', $company_id)->first(); ?>
@if (!empty($template) && $ActivePackageData->mail_temp_status == '1')
    <?php
    
    $company_link = $webUrl ? $webUrl : '';
    
    $pattern = '/\[survey\[(.*?)\]\]/';
    preg_match_all($pattern, $template, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $surveyValue) {
            $surveyFrom = Helper::getSurveyFrom($surveyValue);
            $survey_link = $company_link . '/survey' . '/' . $surveyFrom->slug;
    
            $template = str_replace('[survey[' . $surveyValue . ']]', $survey_link, $template);
        }
    }
    
    echo $template;
    ?>
@endif
