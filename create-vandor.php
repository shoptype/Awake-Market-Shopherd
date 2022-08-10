<?php
//code snipet to crete vandor in specific store
//short code [create-store]
function create_store() {

    //Check if user come from form
    if (isset($_POST['createstore'])) {
        $message = '';
        $backendUrl = 'https://dev-backend.shoptype.com';
        if (isset($_POST['store-token'])) {
            $token = $_POST['store-token'];
        } else {
            $message = 'invalid token';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return;
        }

      //Authorised tokon for creating dokan store
        try {
            $headers = array("authorization: " . $token, "origin:https://dev.shoptype.com", "referer: https://dev.shoptype.com");
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
            $site_url = site_url('', 'https');
        
        if (isset($user->ID)) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            $redirect_to = "{$site_url}/dashboard";
            wp_safe_redirect($redirect_to);
                    }
        //Send vandor data to the shoptype
        try {
            $woocomercedata = array(
                'storeName' => $site_url, 
                'storeHostUrl' => $site_url, 
                'consumerKey' => "ck_86a34cccae27a5babe1b3e07240182efe7fc5625", 
                'consumerSecret' => "cs_db669378a1bf27ec197b1127ddd36b6f19159590", 
                'dokan_vendor_id' => strval($user->ID), 
                'dokan_vendor_name' => $st_user->vendors[0]->name, 
                'enableCheckoutShoptype' => true, 
                'shippingServiceId' => "001",
                'restrictions'=>array('isAdult'=> "false", 'isAgeRestricted'=>"false")

        );
                        $payload = json_encode($woocomercedata);
            print_r($payload);
            
            // Prepare new cURL resource
            $ch = curl_init('https://dev-backend.shoptype.com/store/woo-commerce');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Set HTTP Header for POST request
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "authorization:".$token,"origin:https://dev.shoptype.com", "referer: https://dev.shoptype.com"));
            // Submit the POST request
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // Close cURL session handle
            // print_r(curl_getinfo($ch));
            //echo 'HTTP code: ' . $httpcode;
            curl_close($ch);
        }
        catch(Exception $e) {
            $message = 'Not able to send vandor data to woocomerce';
            echo '<div class"statusMsg"><p>' . $message . '</p></div>';
            return false;
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
