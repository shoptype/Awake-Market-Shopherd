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
    curl_close($ch);
// Close cURL session handle
  }
  catch(Exception $e) {
    
    $message='invalid token';
    echo '<div class"statusMsg"><p>'.$message.'</p></div>';
    return false;

  
  }
if( empty( $result ) ) {$message='invalid token'; echo '<div class"statusMsg"><p>'.$message.'</p></div>'; return false;}
 
 ?>
<div class="store-header">
<div class="store-header-banner"></div>
<div class="user-info">
<div class="user-image">
<img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png">
	</div>
<div class="user-des">

<div class="user-name">
@ <?php echo $st_user->name; ?>
	</div>
	</div>

	</div>
</div>
<!-- Status message -->
<div class="statusMsg"></div>

<!-- File upload form -->
<div class="col-lg-12">
    <form id="storeForm" enctype="multipart/form-data" method='post' action=''>
        
        <div class="form-group">
            
            <label for="name">Store Name</label>
          <input type="text" class="form-control" id="store-name" name="store-name" value="<?php echo $st_user->vendors[0]->name; ?>" readonly />
        </div>
        <div class="form-flex">
        
        <div class="form-group">
              <label for="currency">Currency</label>
  <select id="store-currency" name="store-currency" class="form-select">
    <option value="CAD">CAD</option>
    <option value="USD">USD</option>
    <option value="INR">INR</option>
    
  </select>
        </div>
        <div class="form-group">
         
              <label for="Address">Address</label>
  <input type="text" name="store-Address" class="form-address">
        </div>
        
      </div>
      <div class="form-group">
         
  <input type="text" name="store-Address1" class="form-address1">
        </div>

        <div class="form-flex">
        
        <div class="form-group">
         
              <label for="city">City</label>
  <input type="text" name="store-city" class="form-city">
        </div>
        <div class="form-group">
         
              <label for="zip">Zip</label>
  <input type="text" name="store-zip" class="form-zip">
        </div>
        
      </div>

      <div class="form-flex">
        
        <div class="form-group">
         
              <label for="Country">Country</label>
  <select id="st-country" name="country" class="form-control">
<option value="">Select country</option>
</select>
        </div>
        <div class="form-group">
         
              <label for="State">State</label>
<select id="st-state" name="State" class="form-control"></select>
    
      </div>
        
      </div>
<div class="file-upload">
  <label for="store-image">Store image</label>

  <div class="image-upload-wrap">
    <input class="file-upload-input" type='file' name='fileToUpload' id='fileToUpload' onchange="readURL(this);" accept="image/*" />
    <div class="drag-text">
      JPG. PNG
<br>
You can also upload files
<br>
		<span style="text-decoration:underline;color:blue;padding-top:15px;">Click here</span>
<br>
    </div>
  </div>
  <div class="file-upload-content">
    <img class="file-upload-image" src="#" alt="your image" />
    <div class="image-title-wrap">
      <button type="button" onclick="removeUpload()" class="remove-image">Remove <span class="image-title">Uploaded Image</span></button>
    </div>
  </div>
</div>
        <div class="form-group">
          
            <input type="hidden" class="form-control" id="store-token" name="store-token" value="<?php echo $_GET['token']?>" />
           
        </div>
        <input type="submit" name="createstore" class="btn btn-success submitBtn" value="Save"/>
    </form>
</div>

<style>
.store-header
	{
		}
.store-header-banner
	{
margin-left:-30%;

margin-right:-16.5%;
width:100v;
height: 250px;

background: #D9D9D9;
	}

.user-info{
position: relative;
width: 100%;
height: 150px;
top: -75px;
margin:auto;
gap:30px;
background: #FFFFFF;
box-shadow: 0px 5px 23px rgba(0, 0, 0, 0.08);
border-radius: 10px;
display:flex;
padding:20px;
}
.user-image{
width: 110px;
height: 110px;

background: #FFFFFF;
border-radius: 5px;
	}
.user-des{
font-family: 'Poppins';
font-style: normal;
font-weight: 700;
 margin-top: 10px;
font-size: 20px;
line-height: 30px;
	}
/* identical to box height */

.submitBtn
	{
margin:auto !important;
	width: 100px;
    height: 46px;
    left: 485px;
    top: 983px;
    border: 1px solid #959090;
    border-radius: 10px;
    color: #686868;
    text-align: center;
    margin: auto;
    display: flex;
    justify-content: center;
    align-items: center;
	}
color: #808080;
}
.user-name{
font-family: 'Poppins';
font-style: normal;
font-weight: 700;
font-size: 20px;
line-height: 30px;
/* identical to box height */


color: #808080;
}
  .form-flex
  {
    display: flex;
  }
.form-group {
    margin: 10px;
   
}
.col-lg-12
	{
max-width: 600px;
 margin: auto;
	}
.form-flex .form-group {
width:100%;	
}
input.btn.btn-success.submitBtn {
    margin-left: 2%;
}
input#store-image {
    margin-top: 10px;
}
input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea{
border-radius:5px;
border-color:#959090;
border-width:2px;
padding-left:10px;
padding-right:10px;

		
	}
.file-upload {
  margin: 0 auto;
  padding: 20px;
}

label {
    padding-bottom: 10px;
	}

.file-upload-content {
  display: none;
  text-align: center;
}

.file-upload-input {
  position: absolute;
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  outline: none;
  opacity: 0;
  cursor: pointer;
}

.image-upload-wrap {
  margin-top: 20px;
  border: 4px dashed #B8B7B7;
  position: relative;
}

.image-dropping,
.image-upload-wrap:hover {
  background-color: #1FB264;
  border: 4px dashed #B8B7B7;
}

.image-title-wrap {
  padding: 0 15px 15px 15px;
  color: #222;
}

.drag-text {
padding-top:35px;
  text-align: center;
padding-bottom:35px;
}

.drag-text h3 {
  font-weight: 100;
  text-transform: uppercase;
  color: #15824B;
  padding: 60px 0;
}

.file-upload-image {
  max-height: 200px;
  max-width: 200px;
  margin: auto;
  padding: 20px;
}

.remove-image {
  width: 200px;
  margin: 0;
  color: #fff;
  background: #cd4535;
  border: none;
  padding: 10px;
  border-radius: 4px;
  border-bottom: 4px solid #b02818;
  transition: all .2s ease;
  outline: none;
  text-transform: uppercase;
  font-weight: 700;
}

.remove-image:hover {
  background: #c13b2a;
  color: #ffffff;
  transition: all .2s ease;
  cursor: pointer;
}

.remove-image:active {
  border: 0;
  transition: all .2s ease;
}
input[type=date], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea
{
  width: 100%;
}
</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/shoptype/Shoptype-JS@2.7.11/shoptype.js"></script>
<script>

  function setStoreUrl()
  {
var storeCurrency= jQuery('#store-currency').val();
if(storeCurrency === 'CAD')
{
 jQuery('#storeForm').attr('action', "http://localhost/coseller/add-store/?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdXRob3JpemVkIjp0cnVlLCJpZCI6ImU3YzRkNWYzLWVjMTEtZjhhNy00ZmFhLWUwNDc3NGRhYmFhYiIsInVzZXJJZCI6ImJmMmU5NDBlLTQ4MTgtNDBhMy1iNjE3LTBkZTRiYzY4ZjA4YiIsInVzZXJUeXBlIjoidmVuZG9yIiwiaXNzIjoic2hvcC10eXBlIn0.BbfhRpzmspL7d6U9XR4SdfV33CkGLv-cnRKZgUeUGtE")
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
	setCountry();

    });
  
 function readURL(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      jQuery('.image-upload-wrap').hide();

      jQuery('.file-upload-image').attr('src', e.target.result);
      jQuery('.file-upload-content').show();

      jQuery('.image-title').html(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]);

  } else {
    removeUpload();
  }
}

function removeUpload() {
  jQuery('.file-upload-input').replaceWith(jQuery('.file-upload-input').clone());
  jQuery('.file-upload-content').hide();
  jQuery('.image-upload-wrap').show();
}
jQuery('.image-upload-wrap').bind('dragover', function () {
		jQuery('.image-upload-wrap').addClass('image-dropping');
	});
	jQuery('.image-upload-wrap').bind('dragleave', function () {
		jQuery('.image-upload-wrap').removeClass('image-dropping');
});
   
</script>

  <?php
//<div class="statusMsg"><p>Faild to crete store</p></div>
//<?php 
    return '';
}

add_shortcode('create-storemain', 'create_storemain');
