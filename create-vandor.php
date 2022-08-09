<?php
//short code [create-store]
function create_store() {

	//Check if user come from form
    if (isset($_POST['createstore'])) {
        $message = '';
        $backendUrl = 'https://beta-backend.shoptype.com';
        if (isset($_POST['store-token'])) {
            $token = $_POST['store-token'];
        } else {
            $message = 'invalid token';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return;
        }

      //Authorised tokon for creating dokan store
        try {
            $headers = array("authorization: " . $token, "origin: https://beta.shoptype.com", "referer: https://beta.shoptype.com/");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$backendUrl}/me");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        catch(Exception $e) {
            $message = 'invalid token';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return false;
        }
        if (empty($result)) {
            $message = 'invalid token';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return false;
        }
        $st_user = json_decode($result);

        //create user

        $user = get_user_by('email', "{$st_user->email}");
        if (empty($user)) {
            $user_id = wp_insert_user(array('user_login' => $st_user->email, 'user_pass' => $token, 'user_email' => $st_user->email, 'first_name' => $st_user->name, 'display_name' => $st_user->name, 'role' => 'seller'));
            if (!empty($user_id)) {
                $dokan_settings = array('store_name' => $st_user->vendors[0]->name);
                update_user_meta($user_id, 'dokan_enable_selling', 'yes');
                update_user_meta($user_id, 'dokan_profile_settings', $dokan_settings);
                update_user_meta($user_id, 'dokan_store_name', $st_user->vendors[0]->name);
            }
        } else {
            $user->add_role('seller');
            $dokan_settings = array('store_name' => $st_user->vendors[0]->name);
            update_user_meta($user->ID, 'dokan_enable_selling', 'yes');
            update_user_meta($user->ID, 'dokan_profile_settings', $dokan_settings);
            update_user_meta($user->ID, 'dokan_store_name', $st_user->vendors[0]->name);
        }
        $user = get_user_by('email', "{$st_user->email}");
        //print_r($user);

        //Send vandor data to the shoptype
        $site_url = site_url('', 'https');
        try {
            $woocomercedata = array('storeName' => $site_url, 'storeHostUrl' => $site_url, 'consumerKey' => "ck_737d009b7d6d5b9e18217dea814a39d9fe020346", 'consumerSecret' => "cs_001fcdefc8819a72cb7fb2d215618de107ad929f", 'dokan_vendor_id' => $user->ID, 'dokan_vendor_name' => $st_user->vendors[0]->name, 'enableCheckoutShoptype' => true, 'disable_all_products' => true, 'shippingServiceId' => "001");
            //print_r($woocomercedata);
            $payload = json_encode($woocomercedata);
            // Prepare new cURL resource
            $ch = curl_init('https://dev-backend.shoptype.com/store/woo-commerce');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "authorization: " . $token));
            // Submit the POST request
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // Close cURL session handle
            curl_close($ch);
        }
        catch(Exception $e) {
            $message = 'Not able to send vandor data to woocomerce';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return false;
        }
        if (isset($user->ID)) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            $redirect_to = "{$site_url}/dashboard";
            wp_safe_redirect($redirect_to);
            exit();
        }
        $_POST = array();
?>
    <div><p>Store created <a href="/dashboard">click to visit</a></p></div>
    <?php
    }

     else {
?>
<div class="statusMsg"><p>Faild to crete store</p></div>
<?php
    }
    return '';
}
add_shortcode('create-store', 'create_store');
