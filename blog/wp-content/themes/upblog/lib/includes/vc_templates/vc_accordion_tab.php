<?php
$output = $title = $icon = '';

extract(shortcode_atts(array(
    'title' => __("Section", "js_composer"),
    'icon'  => 'fa-plane'
), $atts));

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion_section group', $this->settings['base'], $atts );

$acc_ID = VC_Setup::get_instance()->accordion_instance['id'];
$tab_id = ++VC_Setup::get_instance()->accordion_instance['tab_id'];
$tab_style = VC_Setup::get_instance()->accordion_instance['type'];


$output .= '<div class="panel"><div class="panel-heading"><h4 class="panel-title">';
if($tab_id == 1){
    $output .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-0'.$acc_ID.'" href="#collapse-'.$acc_ID.$tab_id.'">';
} else {
    $output .= '<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-0'.$acc_ID.'" href="#collapse-'.$acc_ID.$tab_id.'">';
}

$output .= '<span></span>';

$output .= $title;
$output .= '</a></h4></div>';
if($tab_id == 1){
    $output .= '<div id="collapse-'.$acc_ID.$tab_id.'" class="panel-collapse collapse in"><div class="panel-body panel-body_simple">';
} else {
    $output .= '<div id="collapse-'.$acc_ID.$tab_id.'" class="panel-collapse collapse"><div class="panel-body panel-body_simple">';
}
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';


echo $output;