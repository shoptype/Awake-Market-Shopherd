<?php

//short code [create-storemain]
function create_storemain() {
$message='';
  $backendUrl='https://beta-backend.shoptype.com';
  if(isset($_GET['token']))
  {
      $token = $_GET['token'];
  }
  else {
    $message='invalid token';
    echo '<div class"statusMsg"><p>'.$message.'</p></div>';
    return;


  }
  
try {
    
    $headers = array(
  
    "authorization: ".$token,
    "origin: https://beta.shoptype.com",
  "referer: https://beta.shoptype.com/"
  );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$backendUrl}/me");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $st_user = json_decode($result);

// Close cURL session handle
  }
  catch(Exception $e) {
    
    $message='invalid token';
    echo '<div class"statusMsg"><p>'.$message.'</p></div>';
    return false;

  
  }
if( empty( $result ) ) {$message='invalid token'; echo '<div class"statusMsg"><p>'.$message.'</p></div>'; return false;}
 
 ?>
<!-- Status message -->
<div class="statusMsg"></div>

<!-- File upload form -->
<div class="col-lg-12">
    <form id="storeForm" enctype="multipart/form-data" method='post' action=''>
        <div class="form-group">
            <label for="name">Email</label>
          <input type="text" class="form-control" id="store-email" name="store-email" value="<?php echo $st_user->email ?>" readonly />
        </div>
        
        <div class="form-group">
            <label for="name">Store Name</label>
          <input type="text" class="form-control" id="store-name" name="store-name" value="<?php echo $st_user->vendors[0]->name; ?>" readonly />
        </div>
        <div class="form-group">
              <label for="currency">Choose a currency:</label>
  <select id="store-currency" name="store-currency" class="form-select">
    <option value="CAD">CAD</option>
    <option value="USD">USD</option>
    <option value="INR">INR</option>
    
  </select>
        </div>
        <div class="form-group">
          
            <input type="hidden" class="form-control" id="store-token" name="store-token" value="<?php echo $_GET['token']?>" />
           
        </div>
        <input type="submit" name="createstore" class="btn btn-success submitBtn" value="Crete Store"/>
    </form>
</div>
<style>
.form-group {
    margin: 25px;
    width: 50%;
}
input.btn.btn-success.submitBtn {
    margin-left: 2%;
}
input#store-image {
    margin-top: 10px;
}
</style>
<script>
  function setStoreUrl()
  {
var storeCurrency= jQuery('#store-currency').val();
if(storeCurrency === 'CAD')
{
 jQuery('#storeForm').attr('action', "/test1")
  }
 if(storeCurrency === 'USD')
{
  jQuery('#storeForm').attr('action', "http://awake.furniture/test-store-create/")
 
  }
  if(storeCurrency === 'INR')
{
  jQuery('#storeForm').attr('action', "http://awake.furniture/test-store-create/")
 
  }   
}
jQuery(document).ready(function(){
   setStoreUrl();
    jQuery('#store-currency').change(setStoreUrl);

    });
  
    
</script>
  <?php
//<div class="statusMsg"><p>Faild to crete store</p></div>
//<?php 
    return '';
}

add_shortcode('create-storemain', 'create_storemain');
