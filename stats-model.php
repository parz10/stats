<?php

namespace Helpie\Features\Components\Stats;

use \Helpie\Includes\Translations as Translations;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Helpie\Features\Components\Stats\Stats_Model')) {
    class Stats_Model extends \Helpie\Includes\Core\Model
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function get_viewProps($args)
        {
            $viewProps = array(
                'collection' => $this->get_collectionProps($args),
                'items' => $this->get_itemsProps(),
            );

            return $viewProps;
        }

        public function get_collectionProps($args)
        {
            $default_args = $this->get_default_args();

            $collectionProps = array_merge($default_args, $args);

            $collectionProps = $this->process($collectionProps);

            return $collectionProps;
        }

        public function process($collectionProps)
        {
            // Convert '1' to 'one' and so on
            $collectionProps['num_of_cols'] = $this->helper->numeric_processing($collectionProps['num_of_cols']);

            return $collectionProps;
        }

        public function get_itemsProps()
        {
            $settings = new \Helpie\Includes\Settings\Getters\Settings();
            $articles = $settings->single_page->get_single_cpt_name_plural();

            return array(
                0 => array(
                    'label' => ucfirst($articles),
                    'value' => $this->get_total_article_count(),
                    'icon_code' => 'file outline icon',
                ),
                1 => array(
                    'label' => Translations::get('contributors'),
                    'value' => $this->get_contributor_count(),
                    'icon_code' => 'user plus icon',
                ),
                2 => array(
                    'label' => Translations::get('topics'),
                    'value' => $this->get_topic_count(),
                    'icon_code' => 'folder outline icon',
                ),

            );
        }

        public function get_total_article_count()
        {

            $count_articles = wp_count_posts('pauple_helpie');
            return $count_articles->publish;
        }

        public function get_contributor_count()
        {
            $count_users = count_users();

            return $count_users['total_users'];
        }

        public function get_topic_count()
        {
            $numTerms = wp_count_terms('helpdesk_category', array(
                'hide_empty' => false,
            ));

            return $numTerms;
        }

        public function count_user_posts_by_type($userid, $post_type = 'post')
        {
            global $wpdb;
            $where = get_posts_by_author_sql($post_type, true, $userid);
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");
            return apply_filters('get_usernumposts', $count, $userid);
        }

        public function get_style_config()
        {
            $style_config = array(
                'collection' => array(
                    'name' => 'helpie_frontend_stats',
                    'selector' => '.helpie-frontend-stats-section',
                    'label' => Translations::get('stats_collection'),
                    'styleProps' => array('background', 'border', 'padding', 'margin'),
                ),
                'title' => array(
                    'name' => 'helpie_frontend_stats_title',
                    'selector' => '.helpie-frontend-stats-section .collection-title',
                    'label' => Translations::get('stats_title'),
                    'styleProps' => array('color', 'typography', 'text-align', 'border', 'padding', 'margin'),
                ),
                'element' => array(
                    'name' => 'helpie_element',
                    'selector' => '.helpie-frontend-stats-section .helpie-element',
                    'label' => Translations::get('single_item'),
                    'styleProps' => array('text-align', 'background', 'border', 'border_radius', 'padding', 'margin'),
                    'children' => array(
                        'title' => array(
                            'name' => 'helpie_element_title',
                            'selector' => '.helpie-frontend-stats-section .helpie-element .count',
                            'label' => Translations::get('single_item_count'),
                            'styleProps' => array('color', 'typography', 'text-align', 'border'),
                        ),
                        'label' => array(
                            'name' => 'helpie_element_label',
                            'selector' => '.helpie-frontend-stats-section .helpie-element .label',
                            'label' => Translations::get('single_item_label'),
                            'styleProps' => array('color', 'typography', 'text-align', 'border'),
                        ),
                        'icon' => array(
                            'name' => 'helpie_element_icon',
                            'selector' => '.helpie-frontend-stats-section .helpie-element i',
                            'label' => Translations::get('single_item_icon'),
                            'styleProps' => array('color', 'text-align'),
                        ),
                    ),
                ),
            );

            return $style_config;
        }

        public function get_default_args()
        {
            $args = $this->get_manual_default_args();

            // No Settings available for second layer of fallback

            return $args;
        }

        public function get_manual_default_args()
        {
            $args = array();

            // Get Default Values from GET - FIELDS
            $fields = $this->get_fields();
            foreach ($fields as $key => $field) {
                $args[$key] = $field['default'];
            }

            return $args;
        }

        public function get_fields()
        {
            $fields = array(
                'title' => $this->get_title_field(),
                'show_title' => $this->get_show_title_field(),
                'num_of_cols' => $this->get_num_of_cols_field(),
            );

            return $fields;
        }

        // FIELDS
        protected function get_title_field()
        {
            return array(
                'name' => 'title',
                'label' => Translations::get('title'),
                'default' => 'Help Center',
                'type' => 'text',
            );
        }

        protected function get_show_title_field()
        {
            return array(
                'name' => 'show_title',
                'label' => Translations::get('display_title'),
                'default' => 'false',
                'options' => array(
                    'true' => Translations::get('true'),
                    'false' => Translations::get('false'),
                ),
                'type' => 'select',
            );
        }

        protected function get_num_of_cols_field()
        {
            return array(
                'name' => 'num_of_cols',
                'label' => Translations::get('num_of_cols'),
                'default' => 'three',
                'options' => array(
                    'one' => 1,
                    'two' => 2,
                    'three' => 3,
                    'four' => 4,
                ),
                'type' => 'select',
            );
        }
    } // END CLASS
}
