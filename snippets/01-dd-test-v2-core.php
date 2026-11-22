/* DD TEST V2 – JADRO, DATABAZA A SPRACOVANIE */

if (!function_exists('ddx_v2_table')) {
    function ddx_v2_table() {
        global $wpdb;
        return $wpdb->prefix . 'ddx_test_results';
    }
}

if (!function_exists('ddx_v2_install')) {
    function ddx_v2_install() {
        global $wpdb;

        $table = ddx_v2_table();

        $exists = $wpdb->get_var(
            $wpdb->prepare("SHOW TABLES LIKE %s", $table)
        );

        if (get_option('ddx_v2_db_ready') && $exists === $table) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE $table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            email varchar(190) NOT NULL,
            answers longtext NOT NULL,
            score int NOT NULL,
            category varchar(40) NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id)
        ) " . $wpdb->get_charset_collate() . ";";

        dbDelta($sql);

        $exists = $wpdb->get_var(
            $wpdb->prepare("SHOW TABLES LIKE %s", $table)
        );

        if ($exists === $table) {
            update_option('ddx_v2_db_ready', 1);
        }
    }
    add_action('init', 'ddx_v2_install');
}

if (!function_exists('ddx_v2_handle')) {
    function ddx_v2_handle() {

        if (
            !isset($_POST['ddx_nonce']) ||
            !wp_verify_nonce(
                sanitize_text_field(wp_unslash($_POST['ddx_nonce'])),
                'ddx_v2_submit'
            )
        ) {
            wp_die('Platnosť formulára vypršala. Obnovte stránku a skúste to znova.');
        }

        $email = isset($_POST['ddx_email'])
            ? sanitize_email(wp_unslash($_POST['ddx_email']))
            : '';

        if (!is_email($email)) {
            wp_die('Zadajte platnú e-mailovú adresu.');
        }

        if (empty($_POST['ddx_terms'])) {
            wp_die('Potvrďte, že ste sa oboznámili s informáciami.');
        }

        $answers = array();
        $score = 0;

        for ($i = 1; $i <= 13; $i++) {

            $key = 'q' . $i;

            $value = isset($_POST[$key])
                ? sanitize_text_field(wp_unslash($_POST[$key]))
                : '';

            if (
                $i <= 6 ||
                $i === 9 ||
                $i === 12
            ) {
                $allowed = array(
                    'nikdy',
                    'zriedkavo',
                    'často',
                    'veľmi často'
                );
            } else {
                $allowed = array('áno', 'nie');
            }

            if (!in_array($value, $allowed, true)) {
                wp_die('Vyplňte všetky otázky. Potom odošlite test znova.');
            }

            $answers[$key] = $value;

            if (
                $i <= 6 ||
                $i === 9 ||
                $i === 12
            ) {
                $points = array(
                    'nikdy' => 0,
                    'zriedkavo' => 1,
                    'často' => 2,
                    'veľmi často' => 3
                );

                $score += $points[$value];

            } elseif ($value === 'áno') {

                if (in_array($i, array(7, 11, 13), true)) {
                    $score += 2;
                } else {
                    $score += 1;
                }
            }
        }

        if ($score <= 9) {
            $category = 'Nízke riziko';
        } elseif ($score <= 20) {
            $category = 'Zvýšené riziko';
        } else {
            $category = 'Vysoké riziko';
        }

        global $wpdb;

        $inserted = $wpdb->insert(
            ddx_v2_table(),
            array(
                'email' => $email,
                'answers' => wp_json_encode($answers),
                'score' => $score,
                'category' => $category,
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%d', '%s', '%s')
        );

        if (!$inserted) {
            wp_die('Výsledok sa nepodarilo uložiť. Skúste to neskôr.');
        }

        $token = wp_generate_password(24, false, false);

        set_transient(
            'ddx_v2_result_' . $token,
            array(
                'score' => $score,
                'category' => $category
            ),
            DAY_IN_SECONDS
        );

        $back = isset($_POST['ddx_return'])
            ? esc_url_raw(wp_unslash($_POST['ddx_return']))
            : home_url('/test-novy/');

        $back = wp_validate_redirect(
            $back,
            home_url('/test-novy/')
        );

        wp_safe_redirect(
            add_query_arg('dd_result', $token, $back)
        );

        exit;
    }

    add_action('admin_post_ddx_v2_submit', 'ddx_v2_handle');
    add_action('admin_post_nopriv_ddx_v2_submit', 'ddx_v2_handle');
}