<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Custom_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom_menu_widget'; // Unique name for the widget
    }

    public function get_title() {
        return __( 'Custom Menu Widget', 'custom-menu-widget' ); // Title displayed in Elementor
    }

    public function get_icon() {
        return 'eicon-nav-menu'; // Icon for the widget
    }

    public function get_categories() {
        return [ 'general' ]; // Category for the widget
    }

    protected function _register_controls() {
        // Debugging line to log when controls are registered
        error_log('Custom Menu Widget Controls Registered');

        // Menu Content Section
        $this->start_controls_section(
            'menu_content',
            [
                'label' => __( 'Menu Content', 'custom-menu-widget' ),
            ]
        );

        // Existing Menu Selection
        $this->add_control(
            'existing_menu',
            [
                'label' => __( 'Select Existing Menu', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
                'default' => '',
            ]
        );

        // Custom Menu Items Control
        $this->add_control(
            'menu_items',
            [
                'label' => __( 'Custom Menu Items', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'menu_item_name',
                        'label' => __( 'Menu Item Name', 'custom-menu-widget' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __( 'Menu Item', 'custom-menu-widget' ),
                    ],
                    [
                        'name' => 'menu_item_link',
                        'label' => __( 'Menu Item Link', 'custom-menu-widget' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'default' => [
                            'url' => '#',
                        ],
                    ],
                    [
                        'name' => 'submenu_items',
                        'label' => __( 'Submenu Items', 'custom-menu-widget' ),
                        'type' => \Elementor\Controls_Manager::REPEATER,
                        'fields' => [
                            [
                                'name' => 'submenu_type',
                                'label' => __( 'Select Submenu Type', 'custom-menu-widget' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'options' => [
                                    'link' => __( 'Submenu Item', 'custom-menu-widget' ),
                                    'dynamic' => __( 'Dynamic Product Category', 'custom-menu-widget' ),
                                ],
                                'default' => 'link',
                            ],
                            [
                                'name' => 'submenu_item_name',
                                'label' => __( 'Submenu Item Name', 'custom-menu-widget' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'default' => __( 'Submenu Item', 'custom-menu-widget' ),
                                'condition' => [
                                    'submenu_type' => 'link', // Show only if Submenu Item is selected
                                ],
                            ],
                            [
                                'name' => 'submenu_item_link',
                                'label' => __( 'Submenu Item Link', 'custom-menu-widget' ),
                                'type' => \Elementor\Controls_Manager::URL,
                                'default' => [
                                    'url' => '#',
                                ],
                                'condition' => [
                                    'submenu_type' => 'link', // Show only if Submenu Item is selected
                                ],
                            ],
                            [
                                'name' => 'dynamic_product_categories',
                                'label' => __( 'Dynamic Product Categories', 'custom-menu-widget' ),
                                'type' => \Elementor\Controls_Manager::SELECT2,
                                'options' => $this->get_product_categories(),
                                'label_block' => true,
                                'multiple' => true,
                                'condition' => [
                                    'submenu_type' => 'dynamic', // Show only if Dynamic Product Category is selected
                                ],
                            ],
                        ],
                        'title_field' => '{{{ submenu_item_name }}}',
                    ],
                ],
                'title_field' => '{{{ menu_item_name }}}',
            ]
        );

        // Icon Control for Dropdown
        $this->add_control(
            'dropdown_icon',
            [
                'label' => __( 'Dropdown Icon', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Menu Layout Control
        $this->add_control(
            'menu_layout',
            [
                'label' => __( 'Menu Layout', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'custom-menu-widget' ),
                    'vertical' => __( 'Vertical', 'custom-menu-widget' ),
                ],
                'default' => 'horizontal',
            ]
        );

        // Columns Control
        $this->add_control(
            'submenu_columns',
            [
                'label' => __( 'Number of Columns', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
                'step' => 1,
            ]
        );

        // Gap Control
        $this->add_control(
            'submenu_gap',
            [
                'label' => __( 'Gap Between Submenu Items', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
                'step' => 1,
            ]
        );

        // Hover Color Control
        $this->add_control(
            'submenu_hover_color',
            [
                'label' => __( 'Submenu Item Hover Color', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .custom-menu-widget .submenu li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Submenu Background Color Control
        $this->add_control(
            'submenu_background_color',
            [
                'label' => __( 'Submenu Background Color', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .custom-menu-widget .submenu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Menu Background Color Control
        $this->add_control(
            'menu_background_color',
            [
                'label' => __( 'Menu Background Color', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .custom-menu-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Font Size Controls
        $this->add_control(
            'menu_font_size',
            [
                'label' => __( 'Menu Font Size', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-menu-widget > ul > li > a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_font_size',
            [
                'label' => __( 'Submenu Font Size', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-menu-widget .submenu li a' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .custom-menu-widget .submenu li .product-count' => 'font-size: {{SIZE}}{{UNIT}};', // Apply to quantity
                ],
            ]
        );

        // Alignment Control
        $this->add_control(
            'menu_alignment',
            [
                'label' => __( 'Menu Alignment', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );

        // Submenu Alignment Control
        $this->add_control(
            'submenu_alignment',
            [
                'label' => __( 'Submenu Alignment', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );

        // Submenu Box Style Controls
        $this->add_control(
            'submenu_box_shadow',
            [
                'label' => __( 'Submenu Box Shadow', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::BOX_SHADOW,
                'default' => [],
            ]
        );

        $this->add_control(
            'submenu_border_radius',
            [
                'label' => __( 'Submenu Border Radius', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_control(
            'submenu_padding',
            [
                'label' => __( 'Submenu Padding', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
            ]
        );

        // Font Options for Submenu Items
        $this->add_control(
            'submenu_font_family',
            [
                'label' => __( 'Submenu Font Family', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::FONT,
                'default' => 'default',
            ]
        );

        $this->add_control(
            'submenu_font_size',
            [
                'label' => __( 'Submenu Font Size', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
            ]
        );

        // Mobile Dropdown Options
        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => __( 'Mobile Breakpoint', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'mobile' => __( 'Mobile Portrait (> 767px)', 'custom-menu-widget' ),
                    'tablet' => __( 'Tablet Portrait (> 1024px)', 'custom-menu-widget' ),
                    'none' => __( 'None', 'custom-menu-widget' ),
                ],
                'default' => 'mobile',
            ]
        );

        $this->add_control(
            'toggle_button_style',
            [
                'label' => __( 'Toggle Button Style', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'hamburger' => __( 'Hamburger', 'custom-menu-widget' ),
                    'dropdown' => __( 'Dropdown', 'custom-menu-widget' ),
                ],
                'default' => 'hamburger',
            ]
        );

        $this->add_control(
            'icon_button_option',
            [
                'label' => __( 'Icon Button Option', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'mobile_toggle_align',
            [
                'label' => __( 'Mobile Toggle Alignment', 'custom-menu-widget' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'custom-menu-widget' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <nav class="custom-menu-widget" style="text-align: <?php echo esc_attr( $settings['menu_alignment'] ); ?>;">
            <ul>
                <?php
                if ( ! empty( $settings['existing_menu'] ) ) {
                    // Display existing menu items
                    $menu_items = wp_get_nav_menu_items( $settings['existing_menu'] );
                    foreach ( $menu_items as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?>
                                <?php if ( ! empty( $item->children ) ) : ?>
                                    <span class="dropdown-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['dropdown_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                                <?php endif; ?>
                            </a>
                            <?php if ( ! empty( $item->children ) ) : ?>
                                <ul class="submenu" style="
                                    background-color: <?php echo esc_attr( $settings['submenu_background_color'] ); ?>; 
                                    column-count: <?php echo esc_attr( $settings['submenu_columns'] ); ?>; 
                                    box-shadow: <?php echo esc_attr( $settings['submenu_box_shadow']['horizontal'] . ' ' . $settings['submenu_box_shadow']['vertical'] . ' ' . $settings['submenu_box_shadow']['blur'] . ' ' . $settings['submenu_box_shadow']['spread'] . ' ' . $settings['submenu_box_shadow']['color']); ?>; 
                                    border-radius: <?php echo esc_attr( $settings['submenu_border_radius']['size'] . $settings['submenu_border_radius']['unit']); ?>; 
                                    padding: <?php echo esc_attr( $settings['submenu_padding']['size'] . $settings['submenu_padding']['unit']); ?>;">
                                    <?php foreach ( $item->children as $submenu ) : ?>
                                        <li>
                                            <a href="<?php echo esc_url( $submenu->url ); ?>"><?php echo esc_html( $submenu->title ); ?></a>
                                            <?php if ( 'dynamic' === $submenu['submenu_type'] ) : ?>
                                                <?php $product_count = $this->get_product_count( $submenu['dynamic_product_categories'] ); ?>
                                                <span class="product-count">(<?php echo esc_html( $product_count ); ?>)</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach;
                } else {
                    // Display custom menu items
                    foreach ( $settings['menu_items'] as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item['menu_item_link']['url'] ); ?>"><?php echo esc_html( $item['menu_item_name'] ); ?>
                                <?php if ( ! empty( $item['submenu_items'] ) ) : ?>
                                    <span class="dropdown-icon"><?php \Elementor\Icons_Manager::render_icon( $settings['dropdown_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                                <?php endif; ?>
                            </a>
                            <?php if ( ! empty( $item['submenu_items'] ) ) : ?>
                                <ul class="submenu" style="
                                    background-color: <?php echo esc_attr( $settings['submenu_background_color'] ); ?>; 
                                    column-count: <?php echo esc_attr( $settings['submenu_columns'] ); ?>; 
                                    box-shadow: <?php echo esc_attr( $settings['submenu_box_shadow']['horizontal'] . ' ' . $settings['submenu_box_shadow']['vertical'] . ' ' . $settings['submenu_box_shadow']['blur'] . ' ' . $settings['submenu_box_shadow']['spread'] . ' ' . $settings['submenu_box_shadow']['color']); ?>; 
                                    border-radius: <?php echo esc_attr( $settings['submenu_border_radius']['size'] . $settings['submenu_border_radius']['unit']); ?>; 
                                    padding: <?php echo esc_attr( $settings['submenu_padding']['size'] . $settings['submenu_padding']['unit']); ?>;">
                                    <?php foreach ( $item['submenu_items'] as $submenu ) : ?>
                                        <?php if ( 'link' === $submenu['submenu_type'] ) : ?>
                                            <li>
                                                <a href="<?php echo esc_url( $submenu['submenu_item_link']['url'] ); ?>"><?php echo esc_html( $submenu['submenu_item_name'] ); ?></a>
                                            </li>
                                        <?php elseif ( 'dynamic' === $submenu['submenu_type'] ) : ?>
                                            <?php foreach ( $submenu['dynamic_product_categories'] as $category_id ) : ?>
                                                <?php $category = get_term( $category_id, 'product_cat' ); ?>
                                                <li>
                                                    <a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                                                    <?php $product_count = $this->get_product_count( $category_id ); ?>
                                                    <span class="product-count" style="font-size: <?php echo esc_attr( $settings['submenu_font_size']['size'] . $settings['submenu_font_size']['unit'] ); ?>;">
                                                        (<?php echo esc_html( $product_count ); ?>)
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach;
                }
                ?>
            </ul>
        </nav>
        <style>
            .custom-menu-widget ul {
                list-style: none;
                padding: 0;
                margin: 0;
                font-weight: bold;
            }
            .custom-menu-widget > ul {
                display: flex;
                flex-direction: <?php echo esc_attr( $settings['menu_layout'] ); ?>;
            }
            .custom-menu-widget li {
                position: relative;
                margin: 0 5px; /* Adjust this if you want to change the spacing between main menu items */
            }
            .custom-menu-widget .submenu {
                position: absolute;
                top: 100%;
                left: 0;
                font-weight: normal;
                display: none; /* Default to hidden */
                width: auto; /* Adjust width as needed */
                column-count: <?php echo esc_attr( $settings['submenu_columns'] ); ?>; /* Set number of columns */
                column-gap: <?php echo esc_attr( $settings['submenu_gap'] ); ?>px; /* Set gap between columns */
            }
            .custom-menu-widget li:hover > .submenu {
                display: block; /* Show on hover of the parent menu item */
            }
            .custom-menu-widget .submenu li {
                margin: 0; /* Remove margin to minimize gap */
                padding: <?php echo esc_attr( $settings['submenu_padding']['size'] . $settings['submenu_padding']['unit']); ?>; /* Add padding for better click area, adjust as needed */
            }
            .custom-menu-widget .submenu li:hover {
                background-color: <?php echo esc_attr( $settings['submenu_hover_color'] ); ?>; /* Hover color for submenu items */
            }
            .dropdown-icon {
                margin-left: 5px; /* Space between text and icon */
                display: inline-block; /* Ensure icon is inline */
            }
            .mobile-menu-toggle {
                display: none; /* Hide by default */
            }

            @media (max-width: 768px) { /* Adjust this breakpoint as needed */
                .mobile-menu-toggle {
                    display: block; /* Show on mobile */
                }
                .custom-menu-widget > ul {
                    display: none; /* Hide the main menu on mobile */
                }
                .mobile-menu {
                    display: block; /* Show mobile menu */
                }
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                // Handle click event for submenu display
                $('.custom-menu-widget > ul > li > a').on('click', function(e) {
                    var submenu = $(this).siblings('.submenu');
                    if (submenu.length) {
                        e.preventDefault(); // Prevent default link behavior
                        if (submenu.is(':visible')) {
                            submenu.hide();
                        } else {
                            $('.custom-menu-widget .submenu').hide(); // Hide other submenus
                            submenu.show();
                        }
                    }
                });
                $('.mobile-menu-toggle').on('click', function() {
                    $('.custom-menu-widget > ul').slideToggle(); // Toggle main menu
                });
            });
        </script>
        <?php
        // Add a button for mobile toggle
        echo '<div class="mobile-menu-toggle" style="cursor: pointer;">☰</div>'; // Hamburger icon

        // Add a div for the mobile menu
        echo '<div class="mobile-menu" style="display: none;">';
        echo '<ul class="submenu">'; // Use the same submenu structure
        // Loop through menu items for mobile
        foreach ( $settings['menu_items'] as $item ) {
            echo '<li><a href="' . esc_url( $item['menu_item_link']['url'] ) . '">' . esc_html( $item['menu_item_name'] ) . '</a></li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    private function get_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        foreach ( $menus as $menu ) {
            $options[$menu->term_id] = $menu->name;
        }
        return $options;
    }

    private function get_product_categories() {
        $categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
        $options = [];
        foreach ( $categories as $category ) {
            $options[$category->term_id] = $category->name;
        }
        return $options;
    }

    private function get_product_count( $category_id ) {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ],
            ],
        ];
        $query = new WP_Query( $args );
        return $query->found_posts;
    }
}
