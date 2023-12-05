<?php

namespace Helpie\Features\Components\Stats;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Helpie\Features\Components\Stats\Frontend_Stats')) {
    class Frontend_Stats
    {

        public function __construct()
        {

            $this->stats_model = new \Helpie\Features\Components\Stats\Stats_Model();
        }

        public function get_view($args = array())
        {
            $viewProps = $this->stats_model->get_viewProps($args);
            $collectionProps = $viewProps['collection'];
            $itemsProps = $viewProps['items'];
            $num_of_cols = $collectionProps['num_of_cols'];

            $html = "<div class='content-section helpie-frontend-stats-section'>";

            $show_title_true = ($collectionProps['show_title'] == 'true');

            if ($show_title_true) {
                $html .= "<h3 class='collection-title'>" . $collectionProps['title'] . "</h3>";
            }

            $html .= "<div class='stats-wrapper ui " . $num_of_cols . " column grid items stackable'>";

            foreach ($itemsProps as $key => $item) {
                $html .= $this->get_single_stat($item['label'], $item['value'], $item['icon_code']);
            }

            $html .= "</div>";
            $html .= "</div>";

            return $html;
        }

        public function get_single_stat($label, $value, $icon_code)
        {
            $html = "<div class='column'>";
            $html .= "<div class='helpie-element single-stat'>";
            $html .= "<div class='stat-col'><i class='" . $icon_code . "' aria-hidden='true'></i></div>";
            $html .= "<div class='stat-col text'><span class='count'>" . $value . "</span><span class='label'>" . $label . "</span></div>";
            $html .= "</div>";
            $html .= "</div>";

            return $html;
        }
    } // END CLASS
}