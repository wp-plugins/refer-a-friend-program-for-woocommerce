<?php
/*/
Plugin Name: Refer a Friend Program for WooCommerce
Plugin URI: http://www.storeya.com/public/referafriend
Description: Automatic Lead Generation plugin driving your customers' friends to your store ready to buy!
Version: 01
Author: StoreYa
Author URI: http://www.storeya.com
/*/

$srff_domain = 'ReferAFriend';
add_action('init', 'srff_init');
add_action('admin_notices', 'srff_admin_notice');
add_filter('plugin_action_links', 'srff_plugin_actions', 10, 2);

function srff_init()
{
    if (function_exists('current_user_can') && current_user_can('manage_options'))
        add_action('admin_menu', 'srff_add_settings_page');
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $options = get_option('scpDisable');
}
function srff_settings()
{
    register_setting('storeya-refer-a-friend-group', 'sshID');
    register_setting('storeya-refer-a-friend-group', 'scpDisable');
    add_settings_section('storeya-refer-a-friend', "Refer a Friend", "", 'storeya-refer-a-friend-group');

}
function plugin_get_version()
{
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
    $plugin_file   = basename((__FILE__));
    return $plugin_folder[$plugin_file]['Version'];
}

function srff_admin_notice()
{
    if (!get_option('sshID'))
        echo ('<div class="error"><p><strong>' . sprintf(__('Refer a Friend program is disabled. Please go to the <a href="%s">plugin page</a> and save a valid secret key to enable it.'), admin_url('options-general.php?page=storeya-refer-a-friend')) . '</strong></p></div>');
}
function srff_plugin_actions($links, $file)
{
    static $this_plugin;
    if (!$this_plugin)
        $this_plugin = plugin_basename(__FILE__);
    if ($file == $this_plugin && function_exists('admin_url')) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=storeya-refer-a-friend') . '">' . __('Settings', $srff_domain) . '</a>';
        array_unshift($links, $settings_link);
    }
    return ($links);
}

    function srff_add_settings_page()
    {
        function srff_settings_page()
        {
            global $srff_domain, $storeya_options;
?>
      <div class="wrap">
        <?php
            screen_icon();
?>
        <h2><?php
            _e('Refer a Friend Program', $srff_domain);
?> <small><?
            echo plugin_get_version();
?></small></h2>
        <div class="metabox-holder meta-box-sortables ui-sortable pointer">
          <div class="postbox" style="float:left;width:33em;margin-right:20px">
            <h3 class="hndle"><span><?php
            _e('Refer a Friend Program - Settings', $srff_domain);
?></span></h3>
            <div class="inside" style="padding: 0 10px">
              <p style="text-align:center"><a href="http://www.storeya.com/public/referafriend" target="_blank" title="<?php
            _e('Convert your visitors to paying customers with StoreYa!', $srff_domain);
?>"><img src="<?php
            echo (plugins_url( 'storeya-refer-a-friend.png', __FILE__ ));
?>" height="200" width="200" alt="<?php
            _e('StoreYa Logo', $srff_domain);
?>" /></a></p>
              <form method="post" action="options.php">
                <?php
            settings_fields('storeya-refer-a-friend-group');
?>
                <p><label for="sshID">Enter seccret key (Don&rsquo;t have one? Follow these <a href="#" onclick="showInst(); return false;" id="steps">steps</a>)
</label></p>
<div id="instructions" style="display:none">
 <ol>
  <li>Visit <a href="http://www.storeya.com/public/referafriend" target="_blank">StoreYa&rsquo;s website</a> and sign up for a Free account.</li>
  <li>Create your referral campaign, set the offer and setup the refer a friend pop ups.</li>
  <li>Copy the refer a friend &rsquo;Secret key&rsquo; displayed at step no.6.</li>
</ol>

</div>
                  <p><input type="text" name="sshID" value="<?php echo get_option('sshID');?>"></p>
                    <p class="submit">
                      <input type="submit" class="button-primary" value="<?php
            _e('Save Changes');
?>" />
                    </p>
                  </form>
</p>
                  <p style="font-size:smaller;color:#999239;background-color:#ffffe0;padding:0.4em 0.6em !important;border:1px solid #e6db55;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px"><?php
            printf(__('Don&rsquo;t have a Refer a Friend Program? No problem! %1$sHave your customers converting their friends to your store!%2$sCreate a <strong>FREE</strong> Refer a Friend Offer Now!%3$s', $srff_domain), '<a href="http://www.storeya.com/public/referafriend" title="', '">', '</a>');
?></p>
<img src="http://www.storeya.com/widgets/admin?p=Rff_WooCommerce_plugin"/>
                  </div>
                </div>

                </div>
              </div>
			  
              
 <script type="text/javascript">function showInst(){ document.getElementById("instructions").style.display = "block";}</script>
              <?php
        }
        add_action('admin_init', 'srff_settings');
        add_submenu_page('options-general.php', __('Refer a Friend', $srff_domain), __('Refer a Friend', $srff_domain), 'manage_options', 'storeya-refer-a-friend', 'srff_settings_page');
    }
	
	add_action( 'woocommerce_thankyou', 'storeya_tracking' );

function storeya_tracking( $order_id ) { 

$order_details = new WC_Order( $order_id );
$subtotal = $order_details->get_order_total(); 
$customer_email = $order_details->billing_email;
$first_name = $order_details->billing_first_name;
$last_name = $order_details->billing_last_name;
$customer_name = $first_name." ".$last_name;
if (get_option('sshID')) {
?>
<!-- Begin StoreYa script -->
<script type="text/javascript">

 //<![CDATA[
 (function(){function load_js(){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src='//www.storeya.com/externalscript/ReferFriend'; var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x)} if(window.attachEvent)window.attachEvent('onload',load_js);else window.addEventListener('load',load_js,false)})();

 var _storeya = _storeya || []; 
 var _storeya_order_details = {
 SiteID: '<?php echo get_option('sshID'); ?>',
 OrderID: '<?php echo $order_id; ?>', 
 SubTotal: '<?php echo $subtotal; ?>',
 Email: '<?php echo $customer_email ; ?>', 
 CustomerName: '<?php echo $customer_name ; ?>', 
 };

 _storeya.push([_storeya_order_details]); 

 //]]>
</script>
<!-- End StoreYa script -->

<?php
}
}

?>