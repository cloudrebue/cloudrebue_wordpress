<?php

/**
 * Get a list of WordPress versions available for installation
 *
 * @return array
 */
function get_wordpress_versions()
{
    $versions = '{"5.8-alpha-51049":"bleeding","5.7.3-alpha-51036":"point","5.7.2":1620870664,"5.7.1":1618466956,"5.7":1615323910,"5.6.2":1614009199,"5.6.1":1612403115,"5.6":1607510561,"5.5.3":1604135302,"5.5.2":1604048028,"5.5.1":1599016376,"5.5":1597196480,"5.4.2":1591904327,"5.4.1":1588268252,"5.4":1586433381,"5.3.2":1578431355,"5.3.1":1576222826,"5.3":1573985840,"5.2.4":1572476910,"5.2.3":1567634400,"5.2.2":1560816000,"5.2.1":1558396800,"5.2":1557187200,"5.1.2":1567641600,"5.1.1":1552435200,"5.1":1550707200,"5.0.6":1567641600,"5.0.4":1552435200,"5.0.3":1546992000,"5.0.2":1545177600,"5.0.1":1544659200,"0":1544054400,"4.9.11":1567641600,"4.9.10":1552435200,"4.9.9":1544659200,"4.9.8":1533168000,"4.9.7":1530748800,"4.9.6":1526515200,"4.9.5":1522713600,"4.9.4":1517875200,"4.9.3":1517788800,"4.9.2":1516060800,"4.9.1":1511913600,"4.9":1510790400,"4.8.10":1567641600,"4.8.9":1552435200,"4.8.8":1544659200,"4.8.7":1530748800,"4.8.6":1522713600,"4.8.5":1516060800,"4.8.4":1511913600,"4.8.3":1509408000,"4.8.2":1505779200,"4.8.1":1501632000,"4.8":1496880000,"4.7.14":1567641600,"4.7.13":1552435200,"4.7.12":1544659200,"4.7.11":1530748800,"4.7.10":1522713600,"4.7.9":1516060800,"4.7.8":1511913600,"4.7.7":1509408000,"4.7.6":1505779200,"4.7.5":1494892800,"4.7.4":1492646400,"4.7.3":1488758400,"4.7.2":1485388800,"4.7.1":1484092800,"4.7":1480982400,"4.6.15":1567641600,"4.6.14":1552435200,"4.6.13":1544659200,"4.6.12":1530748800,"4.6.11":1522713600,"4.6.10":1516060800,"4.6.9":1511913600,"4.6.8":1509408000,"4.6.7":1505779200,"4.6.6":1494892800,"4.6.5":1492646400,"4.6.4":1488758400,"4.6.3":1485388800,"4.6.2":1484092800,"4.6.1":1473206400,"4.6":1471305600,"4.5.18":1567641600,"4.5.17":1552435200,"4.5.16":1544659200,"4.5.15":1530748800,"4.5.14":1522713600,"4.5.13":1516060800,"4.5.12":1511913600,"4.5.11":1509408000,"4.5.10":1505779200,"4.5.9":1494892800,"4.5.8":1492646400,"4.5.7":1488758400,"4.5.6":1485388800,"4.5.5":1484092800,"4.5.4":1473206400,"4.5.3":1466467200,"4.5.2":1462492800,"4.5.1":1461628800,"4.5":1460419200,"4.4.19":1567641600,"4.4.18":1552435200,"4.4.17":1544659200,"4.4.16":1530748800,"4.4.15":1522713600,"4.4.14":1516060800,"4.4.13":1511913600,"4.4.12":1509408000,"4.4.11":1505779200,"4.4.10":1494892800,"4.4.9":1492646400,"4.4.8":1488758400,"4.4.7":1485388800,"4.4.6":1484092800,"4.4.5":1473206400,"4.4.4":1466467200,"4.4.3":1462492800,"4.4.2":1454371200,"4.4.1":1452038400,"4.4":1449532800,"4.3.20":1567641600,"4.3.19":1552435200,"4.3.18":1544659200,"4.3.17":1530748800,"4.3.16":1522713600,"4.3.15":1516060800,"4.3.14":1511913600,"4.3.13":1509408000,"4.3.12":1505779200,"4.3.11":1494892800,"4.3.10":1492646400,"4.3.9":1488758400,"4.3.8":1485388800,"4.3.7":1484092800,"4.3.6":1473206400,"4.3.5":1466467200,"4.3.4":1462492800,"4.3.3":1454371200,"4.3.2":1452038400,"4.3.1":1442275200,"4.3":1439856000,"4.2.24":1567641600,"4.2.23":1552435200,"4.2.22":1544659200,"4.2.21":1530748800,"4.2.20":1522713600,"4.2.19":1516060800,"4.2.18":1511913600,"4.2.17":1509408000,"4.2.16":1505779200,"4.2.15":1494892800,"4.2.14":1492646400,"4.2.13":1488758400,"4.2.12":1485388800,"4.2.11":1484092800,"4.2.10":1473206400,"4.2.9":1466467200,"4.2.8":1462492800,"4.2.7":1454371200,"4.2.6":1452038400,"4.2.5":1442275200,"4.2.4":1438646400,"4.2.3":1437609600,"4.2.2":1430956800,"4.2.1":1430092800,"4.2":1429747200,"4.1.27":1567641600,"4.1.26":1552435200,"4.1.25":1544659200,"4.1.24":1530748800,"4.1.23":1522713600,"4.1.22":1516060800,"4.1.21":1511913600,"4.1.20":1509408000,"4.1.19":1505779200,"4.1.18":1494892800,"4.1.17":1492646400,"4.1.16":1488758400,"4.1.15":1485388800,"4.1.14":1484092800,"4.1.13":1473206400,"4.1.12":1466467200,"4.1.11":1462492800,"4.1.10":1454371200,"4.1.9":1452038400,"4.1.8":1442275200,"4.1.7":1438646400,"4.1.6":1437609600,"4.1.5":1430956800,"4.1.4":1430092800,"4.1.3":1429747200,"4.1.2":1429574400,"4.1.1":1424217600,"4.1":1418860800,"4.0.27":1567641600,"4.0.26":1552435200,"4.0.25":1544659200,"4.0.24":1530748800,"4.0.23":1522713600,"4.0.22":1516060800,"4.0.21":1511913600,"4.0.20":1509408000,"4.0.19":1505779200,"4.0.18":1494892800,"4.0.17":1492646400,"4.0.16":1488758400,"4.0.15":1485388800,"4.0.14":1484092800,"4.0.13":1473206400,"4.0.12":1466467200,"4.0.11":1462492800,"4.0.10":1454371200,"4.0.9":1452038400,"4.0.8":1442275200,"4.0.7":1438646400,"4.0.6":1437609600,"4.0.5":1430870400,"4.0.4":1430092800,"4.0.3":1429747200,"4.0.2":1429574400,"4.0.1":1416441600,"1":1409788800,"3.9.28":1567641600,"3.9.27":1552435200,"3.9.26":1544659200,"3.9.25":1530748800,"3.9.24":1522713600,"3.9.23":1516060800,"3.9.22":1511913600,"3.9.21":1509408000,"3.9.20":1505779200,"3.9.19":1494892800,"3.9.18":1492646400,"3.9.17":1488758400,"3.9.16":1485388800,"3.9.15":1484092800,"3.9.14":1473206400,"3.9.13":1466467200,"3.9.12":1462492800,"3.9.11":1454371200,"3.9.10":1452038400,"3.9.9":1442275200,"3.9.8":1438646400,"3.9.7":1437609600,"3.9.6":1430956800,"3.9.5":1429747200,"3.9.4":1429574400,"3.9.3":1416441600,"3.9.2":1407283200,"3.9.1":1399507200,"3.9":1397606400,"3.8.30":1567641600,"3.8.29":1553126400,"3.8.28":1544659200,"3.8.27":1530748800,"3.8.26":1522713600,"3.8.25":1516060800,"3.8.24":1511913600,"3.8.23":1509408000,"3.8.22":1505779200,"3.8.21":1494892800,"3.8.20":1492646400,"3.8.19":1488758400,"3.8.18":1485388800,"3.8.17":1484092800,"3.8.16":1473206400,"3.8.15":1466467200,"3.8.14":1462492800,"3.8.13":1454371200,"3.8.12":1452038400,"3.8.11":1442275200,"3.8.10":1438646400,"3.8.9":1437609600,"3.8.8":1430956800,"3.8.7":1429747200,"3.8.6":1429574400,"3.8.5":1416441600,"3.8.4":1407283200,"3.8.3":1397433600,"3.8.2":1396915200,"3.8.1":1390435200,"3.8":1386806400,"3.7.30":1567641600,"3.7.29":1553126400,"3.7.28":1544659200,"3.7.27":1530748800,"3.7.26":1522713600,"3.7.25":1516060800,"3.7.24":1511913600,"3.7.23":1509408000,"3.7.22":1505779200,"3.7.21":1494892800,"3.7.20":1492646400,"3.7.19":1488758400,"3.7.18":1485388800,"3.7.17":1484092800,"3.7.16":1473206400,"3.7.15":1466467200,"3.7.14":1462492800,"3.7.13":1454371200,"3.7.12":1452038400,"3.7.11":1442275200,"3.7.10":1438646400,"3.7.9":1437609600,"3.7.8":1430956800,"3.7.7":1429747200,"3.7.6":1429574400,"3.7.5":1416441600,"3.7.4":1407283200,"3.7.3":1397433600,"3.7.2":1396915200,"3.7.1":1383004800,"3.7":1382572800,"3.6.1":1378857600,"3.6":1375315200,"3.5.2":1371772800,"3.5.1":1358985600,"3.5":1355184000,"3.4.2":1346889600,"3.4.1":1340755200,"3.4":1339545600,"3.3.3":1340755200,"3.3.2":1334880000,"3.3.1":1325548800,"3.3":1323648000,"3.2.1":1310428800,"3.2":1309737600,"3.1.4":1309305600,"3.1.3":1306281600,"3.1.2":1303776000,"3.1.1":1301875200,"3.1":1298419200,"3.0.6":1303776000,"3.0.5":1297036800,"3.0.4":1293580800,"3.0.3":1291766400,"3.0.2":1291075200,"3.0.1":1280361600,"2":1276732800,"2.9.2":1266192000,"2.9.1":1262563200,"2.9":1261094400,"2.8.6":1257984000,"2.8.5":1255996800,"2.8.4":1250035200,"2.8.3":1249257600,"2.8.2":1248048000,"2.8.1":1247097600,"2.8":1244678400,"2.7.1":1234224000,"2.7":1228867200,"2.6.5":1227571200,"2.6.3":1224720000,"2.6.2":1220832000,"2.6.1":1218758400,"2.6":1216080000,"2.5.1":1209081600,"2.5":1206748800,"2.3.3":1202169600,"2.3.2":1198886400,"2.3.1":1193356800,"2.3":1190678400,"2.2.3":1190592000,"2.2.2":1190592000,"2.2.1":1190592000,"2.2":1190592000,"2.1.3":1190592000,"2.1.2":1190592000,"2.1.1":1190592000,"2.1":1190592000,"2.0.11":1190592000,"2.0.10":1190592000,"2.0.9":1190592000,"2.0.8":1190592000,"2.0.7":1190592000,"2.0.6":1190592000,"2.0.5":1190592000,"2.0.4":1190592000,"2.0.1":1190592000,"3":1190592000,"1.5.2":1190592000,"1.5.1.3":1190592000,"1.5.1.2":1190592000,"1.5.1.1":1190592000,"1.5.1":1190592000,"1.5-strayhorn":1190592000,"1.2.2":1190592000,"1.2.1":1190592000,"1.2-mingus":1190592000,"1.2-delta":1190592000,"1.0-platinum":1190592000,"1.0.2":1190592000,"1.0.2-blakey":1190592000,"1.0.1-miles":1190592000}';

    $versions = json_decode($versions);

    return $versions;
} // get_wordpress_versions


/**
 * Format file size to human readable string
 *
 * @param int $bytes Size in bytes to format.
 *
 * @return string
 */
function format_size($bytes)
{
    if ($bytes > 1073741824) {
        return number_format_i18n($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes > 1048576) {
        return number_format_i18n($bytes / 1048576, 1) . ' MB';
    } elseif ($bytes > 1024) {
        return number_format_i18n($bytes / 1024, 1) . ' KB';
    } else {
        return number_format_i18n($bytes, 0) . ' bytes';
    }
} // format_size


/**
 * Create select options for select
 *
 * @param array $options options
 * @param string $selected selected value
 * @param bool $output echo, if false return html as string
 * @return string html with options
 */
function create_select_options($options, $selected = null, $output = true)
{
    $out = "\n";

    if (is_array($options) && !empty($options) && !isset($options[0]['val'])) {
        $tmp = array();
        foreach ($options as $val => $label) {
            $tmp[] = array('val' => $val, 'label' => $label);
        } // foreach
        $options = $tmp;
    }

    foreach ($options as $tmp) {
        if ($selected == $tmp['val']) {
            $out .= '<option selected="selected" value="' . esc_attr($tmp['val']) . '">' . esc_html($tmp['label']) . '&nbsp;</option>' . PHP_EOL;
        } else {
            $out .= '<option value="' . esc_attr($tmp['val']) . '">' . esc_html($tmp['label']) . '&nbsp;</option>' . PHP_EOL;
        }
    }

    if ($output) {
        WP_Reset_Utility::wp_kses_wf($out);
    } else {
        return $out;
    }
} // create_select_options


/**
 * Generate card header including title and action buttons
 *
 * @param string  $title  Card title.
 * @param string  $card_id  Card element #ID.
 * @param array  $params  Individual icons arguments
 *
 * @return string
 */
function get_card_header($title, $card_id, $params = array())
{
    $params = shortcode_atts(array(
        'documentation_link' => false,
        'iot_button' => false,
        'collapse_button' => false,
        'create_snapshot' => false,
        'pro' => false
    ), (array) $params);

    if ($params['documentation_link'] === true) {
        $params['documentation_link'] = $card_id;
    }

    $out = '';
    $out .= '<h4 id="' . esc_attr($card_id) . '"><span class="card-name">' . esc_html($title);
    if ($params['pro']) {
        $out .= ' - <a data-feature="' . esc_attr($card_id) . '" class="pro-feature tooltip" title="WP Reset PRO tool" href="#"><span class="pro">PRO</span> tool</a>';
    }
    $out .= '</span>';
    $out .= '<div class="card-header-right">';
    if ($params['documentation_link']) {
        $out .= '<a class="documentation-link tooltip" href="' . esc_url(generate_web_link('documentation_link', '/documentation/')) . '" title="' . __('Open documentation for this tool', 'wp-reset') . '" target="blank"><span class="dashicons dashicons-editor-help"></span></a>';
    }
    if ($params['iot_button']) {
        $out .= '<a class="scrollto tooltip" href="#iot" title="Jump to Index of Tools"><span class="dashicons dashicons-screenoptions"></span></a>';
    }
    if ($params['create_snapshot']) {
        $out .= '<a id="create-new-snapshot-primary" title="Create a new snapshot" href="#" class="button button-primary create-new-snapshot tooltip">' . __('Create Snapshot', 'wp-reset') . '</a>';
    }
    if ($params['collapse_button']) {
        $out .= '<a class="toggle-card tooltip" href="#" title="' . __('Collapse / expand box', 'wp-reset') . '"><span class="dashicons dashicons-arrow-up-alt2"></span></a>';
    }
    $out .= '</div></h4>';

    return $out;
} // get_card_header


/**
 * Generate tool icons and description detailing what it modifies
 *
 * @param bool  $modify_files  Does the tool modify files?
 * @param bool  $modify_db  Does the tool modify the database?
 * @param bool  $plural  Is there more than one tool in the set?
 *
 * @return string
 */
function get_tool_icons($modify_files = false, $modify_db = false, $plural = false)
{
    $out = '';
    $modify_files = (bool) $modify_files;
    $modify_db = (bool) $modify_db;
    $plural = (bool) $plural;

    $out .= '<p class="tool-icons">';
    $out .= '<i class="icon-doc-text-inv' . ($modify_files ? ' red' : '') . '"></i> ';
    $out .= '<i class="icon-database' . ($modify_db ? ' red' : '') . '"></i> ';

    if ($plural) {
        if ($modify_files && $modify_db) {
            $out .= 'these tools <b>modify files &amp; the database</b>';
        } elseif (!$modify_files && $modify_db) {
            $out .= 'these tools <b>modify the database</b> but they don\'t modify any files</b>';
        } elseif ($modify_files && !$modify_db) {
            $out .= 'these tools <b>modify files</b> but they don\'t modify the database</b>';
        }
    } else {
        if ($modify_files && $modify_db) {
            $out .= 'this tool <b>modifies files &amp; the database</b>';
        } elseif (!$modify_files && $modify_db) {
            $out .= 'this tool <b>modifies the database</b> but it doesn\'t modify any files</b>';
        } elseif ($modify_files && !$modify_db) {
            $out .= 'this tool <b>modifies files</b> but it doesn\'t modify the database</b>';
        } else {
            $out .= 'this tool doesn\'t modify files or the database';
        }
    }
    $out .= '</p>';

    return $out;
} // get_tool_icons

function wp_kses_wf($html)
{
    add_filter('safe_style_css', function ($styles) {
        $styles_wf = array(
            'text-align',
            'margin',
            'color',
            'float',
            'border',
            'background',
            'background-color',
            'border-bottom',
            'border-bottom-color',
            'border-bottom-style',
            'border-bottom-width',
            'border-collapse',
            'border-color',
            'border-left',
            'border-left-color',
            'border-left-style',
            'border-left-width',
            'border-right',
            'border-right-color',
            'border-right-style',
            'border-right-width',
            'border-spacing',
            'border-style',
            'border-top',
            'border-top-color',
            'border-top-style',
            'border-top-width',
            'border-width',
            'caption-side',
            'clear',
            'cursor',
            'direction',
            'font',
            'font-family',
            'font-size',
            'font-style',
            'font-variant',
            'font-weight',
            'height',
            'letter-spacing',
            'line-height',
            'margin-bottom',
            'margin-left',
            'margin-right',
            'margin-top',
            'overflow',
            'padding',
            'padding-bottom',
            'padding-left',
            'padding-right',
            'padding-top',
            'text-decoration',
            'text-indent',
            'vertical-align',
            'width',
            'display',
        );

        foreach ($styles_wf as $style_wf) {
            $styles[] = $style_wf;
        }
        return $styles;
    });

    $allowed_tags = wp_kses_allowed_html('post');
    $allowed_tags['input'] = array(
        'type' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'checked' => true,
        'disabled' => true,
        'name' => true,
        'size' => true,
        'placeholder' => true,
        'value' => true,
        'data-*' => true,
        'size' => true,
        'disabled' => true
    );

    $allowed_tags['textarea'] = array(
        'type' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'checked' => true,
        'disabled' => true,
        'name' => true,
        'size' => true,
        'placeholder' => true,
        'value' => true,
        'data-*' => true,
        'cols' => true,
        'rows' => true,
        'disabled' => true
    );

    $allowed_tags['select'] = array(
        'type' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'checked' => true,
        'disabled' => true,
        'name' => true,
        'size' => true,
        'placeholder' => true,
        'value' => true,
        'data-*' => true,
        'multiple' => true,
        'disabled' => true
    );

    $allowed_tags['option'] = array(
        'type' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'checked' => true,
        'disabled' => true,
        'name' => true,
        'size' => true,
        'placeholder' => true,
        'value' => true,
        'selected' => true,
        'data-*' => true
    );
    $allowed_tags['optgroup'] = array(
        'type' => true,
        'style' => true,
        'class' => true,
        'id' => true,
        'checked' => true,
        'disabled' => true,
        'name' => true,
        'size' => true,
        'placeholder' => true,
        'value' => true,
        'selected' => true,
        'data-*' => true,
        'label' => true
    );

    $allowed_tags['a'] = array(
        'href' => true,
        'data-*' => true,
        'class' => true,
        'style' => true,
        'id' => true,
        'target' => true,
        'data-*' => true,
        'role' => true,
        'aria-controls' => true,
        'aria-selected' => true,
        'disabled' => true
    );

    $allowed_tags['div'] = array(
        'style' => true,
        'class' => true,
        'id' => true,
        'data-*' => true,
        'role' => true,
        'aria-labelledby' => true,
        'value' => true,
        'aria-modal' => true,
        'tabindex' => true
    );

    $allowed_tags['li'] = array(
        'style' => true,
        'class' => true,
        'id' => true,
        'data-*' => true,
        'role' => true,
        'aria-labelledby' => true,
        'value' => true,
        'aria-modal' => true,
        'tabindex' => true
    );

    $allowed_tags['span'] = array(
        'style' => true,
        'class' => true,
        'id' => true,
        'data-*' => true,
        'aria-hidden' => true
    );

    $allowed_tags['style'] = array(
        'class' => true,
        'id' => true,
        'type' => true
    );

    $allowed_tags['form'] = array(
        'style' => true,
        'class' => true,
        'id' => true,
        'method' => true,
        'action' => true,
        'data-*' => true
    );

    echo wp_kses($html, $allowed_tags);

    add_filter('safe_style_css', function ($styles) {
        $styles_wf = array(
            'text-align',
            'margin',
            'color',
            'float',
            'border',
            'background',
            'background-color',
            'border-bottom',
            'border-bottom-color',
            'border-bottom-style',
            'border-bottom-width',
            'border-collapse',
            'border-color',
            'border-left',
            'border-left-color',
            'border-left-style',
            'border-left-width',
            'border-right',
            'border-right-color',
            'border-right-style',
            'border-right-width',
            'border-spacing',
            'border-style',
            'border-top',
            'border-top-color',
            'border-top-style',
            'border-top-width',
            'border-width',
            'caption-side',
            'clear',
            'cursor',
            'direction',
            'font',
            'font-family',
            'font-size',
            'font-style',
            'font-variant',
            'font-weight',
            'height',
            'letter-spacing',
            'line-height',
            'margin-bottom',
            'margin-left',
            'margin-right',
            'margin-top',
            'overflow',
            'padding',
            'padding-bottom',
            'padding-left',
            'padding-right',
            'padding-top',
            'text-decoration',
            'text-indent',
            'vertical-align',
            'width'
        );

        foreach ($styles_wf as $style_wf) {
            if (($key = array_search($style_wf, $styles)) !== false) {
                unset($styles[$key]);
            }
        }
        return $styles;
    });
}


/**
 * Helper function for generating links
 *
 * @param string  $placement  Optional. UTM content param.
 * @param string  $page       Optional. Page to link to.
 * @param array   $params     Optional. Extra URL params.
 * @param string  $anchor     Optional. URL anchor part.
 *
 * @return string
 */
function generate_web_link($placement = '', $page = '/', $params = array(), $anchor = '')
{
    $base_url = 'https://cloudrebue.co.ke';

    if ('/' != $page) {
        $page = '/' . trim($page, '/') . '/';
    }
    if ($page == '//') {
        $page = '/';
    }

    if ($placement) {
        $placement = trim($placement);
        $placement = '-' . $placement;
    }

    $parts = array_merge(array('ref' => 'wp-reset-free' . $placement), $params);

    if (!empty($anchor)) {
        $anchor = '#' . trim($anchor, '#');
    }

    $out = $base_url . $page . '?' . http_build_query($parts, '', '&amp;') . $anchor;

    return $out;
} // generate_web_link


/**
 * Add "Open WP Reset Tools" action link to plugins table, left part
 *
 * @param array  $links  Initial list of links.
 *
 * @return array
 */
function plugin_action_links($links)
{
    $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=cloudrebuesms')) . '" title="' . esc_attr(__('Open Settings', 'cloudrebuesms')) . '">' . esc_html(__('Open Settings', 'cloudrebuesms')) . '</a>';

    array_unshift($links, $settings_link);

    return $links;
} // plugin_action_links


/**
 * Add links to plugin's description in plugins table
 *
 * @param array  $links  Initial list of links.
 * @param string $file   Basename of current plugin.
 *
 * @return array
 */
function plugin_meta_links($links, $file)
{
    if ($file !== PLUGIN_BASE_FILE) {
        return $links;
    }

    $support_link = '<a target="_blank" href="https://wordpress.org/support/plugin/cloud-rebue-wpsms/" title="' . __('Get help', 'cloudrebuesms') . '">' . __('Support', 'cloudrebuesms') . '</a>';
    $home_link = '<a target="_blank" href="https://cloudrebue.co.ke/about/plugins/cloud-rebue-sms-plugin/" title="' . __('Plugins Homepage', 'cloudrebuesms') . '">' . __('Plugin Homepage', 'cloudrebuesms') . '</a>';
    $rate_link = '<a target="_blank" href="https://wordpress.org/support/plugin/cloud-rebue-wpsms/reviews/#new-post" title="' . __('Rate the plugin', 'cloudrebuesms') . '">' . __('Rate the plugin ★★★★★', 'cloudrebuesms') . '</a>';

    $links[] = $support_link;
    $links[] = $home_link;
    $links[] = $rate_link;

    return $links;
} // plugin_meta_links


// add admin menu and page
function add_admin_pages()
{
    //page title, menu title, permision, plugin slug, template function callback/views, icon, position
    add_menu_page('Cloud Rebue Settings', 'Cloud Rebue', 'manage_options', 'cloudrebuesms', 'sms_settings_page', 'dashicons-email-alt', 110);

    // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
    add_submenu_page('options-general.php', 'Cloud Rebue', 'Cloud Rebue', 'manage_options', 'cloudrebuesms', 'sms_settings_page');
    add_submenu_page('cloudrebuesms', 'Log History', 'Log History', 'manage_options', 'sms_notifications_log_history', 'sms_logs_page');
}

/**
 * Add powered by text in admin footer
 *
 * @param string  $text  Default footer text.
 *
 * @return string
 */
function admin_footer_text($text)
{
    if (!is_it_plugin_page()) {
        return $text;
    }

    $text = '<i><a href="' . esc_url(generate_web_link('admin_footer')) . '" title="' . esc_attr(__('Visit Cloud Rebue page for more info', 'cloudrebuesms')) . '" target="_blank">Cloud Rebue</a> v' . PLUGIN_CURRENT_VERSION . '. Please <a target="_blank" href="https://wordpress.org/support/plugin/cloud-rebue-wpsms/reviews/#new-post" title="Rate the plugin">rate the plugin <span>★★★★★</span></a> to help us spread the word. Thank you from the Cloud Rebue team!</i>';

    return $text;
} // admin_footer_text


/**
 * Test if we're on CRSMS's admin page
 *
 * @return bool
 */
function is_it_plugin_page()
{
    $current_screen = get_current_screen();

    if (!empty($current_screen->id) && $current_screen->id == 'cloudrebuesms') {
        return true;
    } else {
        return false;
    }
} // is_plugin_page

// if (!function_exists('get_current_screen')) {
//     require_once ABSPATH . '/wp-admin/includes/screen.php';
// }
// $screen = get_current_screen();
// var_dump('<script>alert("' . $screen->id . '")</script>');

function add_plugin_stylesheet()
{
    wp_enqueue_style('cloudrebuesms_1', plugins_url('/css/crsms.css', __FILE__));
    wp_enqueue_style('cloudrebuesms_2', plugins_url('/css/tooltipster.bundle.min.css', __FILE__));
    wp_enqueue_style('cloudrebuesms_3', plugins_url('/css/crsmsui.css', __FILE__));
    wp_enqueue_script('cloudrebuesms_4', plugins_url('/crsmsjs/crsms.js', __FILE__));
    wp_enqueue_script('cloudrebuesms_5', plugins_url('/crsmsjs/crsms-libs.min.js', __FILE__));
    wp_enqueue_script('cloudrebuesms_6', plugins_url('/crsmsjs/crsms-pointers.js', __FILE__));
}
// wp_enqueue_scripts('prefix_bootstrap');
// wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');

// wp_enqueue_style('prefix_bootstrap');
// wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
