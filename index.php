<?php
/*
Plugin Name: Avishi WP PayPal Payment Button
Version: 2.0
Plugin URI: http://tips-tricksandfix.com/avishi-wp-paypal-payment-button
Author: Avishika Web Studio
Author URI: http://avishiwebstudio.com
Description: Wordpress Plugin to insert PayPal Buy now or Pay now Button into Post/Page using Simple shortcode.
*/
?>
<?php
/*  Copyright 2012  Avishika Web Studio  (email : info@avishiwebstudio.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php
    /**
     * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
     */
    add_action( 'admin_init', 'avishi_add_my_stylesheet' );

    /**
     * Enqueue plugin style-file
     */
    function avishi_add_my_stylesheet() {
        // Respects SSL, Style.css is relative to the current file
        wp_register_style( 'avishi-style', plugins_url('style.css', __FILE__) );
        wp_enqueue_style( 'avishi-style' );
    }
?>
<?php
//default option
add_option('avishi_sanbox_link','https://www.sandbox.paypal.com/cgi-bin/webscr');
add_option('avishi_live_link','https://www.paypal.com/cgi-bin/webscr');
add_option('avishi_paypal_environment','sandbox');
add_option('avishi_payment_email','email@domainname.com');
add_option('avishi_payment_currency','USD');
add_option('avishi_payment_button_type','https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif');


function avishi_simeple_paypal_pament($atts, $content = null)
{
	//extract shortcode attributes
	extract(shortcode_atts(array(
		'name' => 'product name',
		'number' => 0001,
		'amount' => 0,
		
		
	), $atts));
	

//get default Options
$avishi_pp_envi       = get_option('avishi_paypal_environment');
$avishi_pp_email = get_option('avishi_payment_email');
$avishi_pp_sandbox = get_option('avishi_sanbox_link');
$avishi_pp_live = get_option('avishi_live_link');
$avishi_pp_currency = get_option('avishi_payment_currency');
$avishi_payment_button = get_option('avishi_payment_button_type');
if ($avishi_pp_envi == "live")
{
   $avishi_pp_site_link = $avishi_pp_live;
}
elseif($avishi_pp_envi == "sandbox")
{
	$avishi_pp_site_link = $avishi_pp_sandbox;
}

//paypal buy now Button form
$output .='
<form action="'.$avishi_pp_site_link.'" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="'.$avishi_pp_email.'">
<input type="hidden" name="lc" value="US">';



$output .= "<input type=\"hidden\" name=\"item_name\" value=\"$name\">";
$output .= "<input type=\"hidden\" name=\"item_number\" value=\"$number\">";
$output .= "<input type=\"hidden\" name=\"amount\" value=\"$amount\">";



$output .= '<input type="hidden" name="currency_code" value="'.$avishi_pp_currency.'">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">';


$output .= "<input type=\"image\" src=\"$avishi_payment_button\" name=\"submit\" alt=\"Make payments with payPal - it's fast, free and secure!\" />";

$output .= '</form>';

return $output;


}



function avishi_add_admin_menu()
{
	add_options_page('Avishi Paypal Payment Accept', 'Avishi PayPal Payment', 5, __FILE__, 'avishi_options_page');
}
add_action('admin_menu', 'avishi_add_admin_menu');
add_shortcode('avishi-paypal-button', 'avishi_simeple_paypal_pament');

function avishi_options_page()
{
	
	
			
	
	
	
	?>
    
   
    
	<div class="wrap">
    
    <div class="titleBox">
    <h1 class="pluginTitle">Avishi Simple Paypal Payment Button </h1>
    <small>Developed by Avishi Web Studio</small>
    </div>
    
    <div class="usageBox" >
    Plugin Usage : 
    
    <ol>
    <li>Add the shortcode <strong>[avishi-paypal-button name="Product Name" number="Product ID" amount="Product Amount"]</strong> to post or page.</li> 
     </ol>
     
     </div>
    
    
    
    
    <?php
	if(isset($_POST['submit']))
	{
		
		
		update_option('avishi_payment_email',$_POST['avishi_payment_email']);
		update_option('avishi_paypal_environment',$_POST['avishi_paypal_environment']);
		update_option('avishi_payment_currency',$_POST['avishi_payment_currency']);
		update_option('avishi_payment_button_type',$_POST['avishi_payment_button_type']);
		echo '<div id="message" class="updated fade"><p><strong>';
		echo "Options updated";
        echo '</strong></p></div>';
	}
	
	$avishi_pp_envi  = get_option('avishi_paypal_environment');
	$avishi_pp_email = get_option('avishi_payment_email');
	$avishi_pp_sandbox = get_option('avishi_sandbox_link');
	$avishi_pp_live = get_option('avishi_live_link');
	$avishi_pp_currency = get_option('avishi_payment_currency');
	$avishi_payment_button_type = get_option('avishi_payment_button_type');
	?>
    
    <br /><br />
    <h2>Required Settings</h2>    
    
    <form name="avishiPPOptions" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">

<table cellpadding="10" cellspacing="0" class="settingTable" >

<tr><td>
  
    <strong>PayPal Payment Email Address</strong>  </td><td>:</td><td> <input type="text" name="avishi_payment_email" class="avishiTextBox" value="<?php echo $avishi_pp_email;   ?>" />
  </td></tr>
   
   
  <tr><td>
   
   <strong>Select Environment</strong></td><td>:</td><td>
   
   <input type="radio" name="avishi_paypal_environment" value="sandbox" <?php if($avishi_pp_envi == "sandbox") echo "checked"; ?> /> Development Environment (Sandbox)
   <br /><br />
   <input type="radio" name="avishi_paypal_environment" value="live" <?php if($avishi_pp_envi =="live") echo "checked"; ?> /> Live Environment
   
   </td></tr>
  
  
  
  <tr><td><strong>Select Your Currency</strong></td><td>:</td>
  <td>
  <select id="avishi_payment_currency" name="avishi_payment_currency">
    <?php _e('<option value="AUD"') ?><?php if ($avishi_pp_currency == "AUD") echo " selected " ?><?php _e('>Australian Dollar (A $)</option>') ?>
<?php _e('<option value="CAD"') ?><?php if ($avishi_pp_currency == "CAD") echo " selected " ?><?php _e('>Canadian Dollar (C $)</option>') ?>
<?php _e('<option value="EUR"') ?><?php if ($avishi_pp_currency == "EUR") echo " selected " ?><?php _e('>Euro (&#8364;)</option>')?>
<?php _e('<option value="GBP"') ?><?php if ($avishi_pp_currency == "GBP") echo " selected " ?><?php _e('>British Pound (&pound;)</option>') ?>
<?php _e('<option value="JPY"') ?><?php if ($avishi_pp_currency == "JPY") echo " selected " ?><?php _e('>Japanese Yen (&yen;)</option>') ?>
<?php _e('<option value="USD"') ?><?php if ($avishi_pp_currency == "USD") echo " selected " ?><?php _e('>U.S. Dollar ($)</option>') ?>
<?php _e('<option value="NZD"') ?><?php if ($avishi_pp_currency == "NZD") echo " selected " ?><?php _e('>New Zealand Dollar ($)</option>') ?>
<?php _e('<option value="CHF"') ?><?php if ($avishi_pp_currency == "CHF") echo " selected " ?><?php _e('>Swiss Franc	</option>') ?>
<?php _e('<option value="HKD"') ?><?php if ($avishi_pp_currency == "HKD") echo " selected " ?><?php _e('>Hong Kong Dollar ($)</option>') ?>
<?php _e('<option value="SGD"') ?><?php if ($avishi_pp_currency == "SGD") echo " selected " ?><?php _e('>Singapore Dollar ($)</option>') ?>
<?php _e('<option value="SEK"') ?><?php if ($avishi_pp_currency == "SEK") echo " selected " ?><?php _e('>Swedish Krona	</option>') ?>
<?php _e('<option value="DKK"') ?><?php if ($avishi_pp_currency == "DKK") echo " selected " ?><?php _e('>Danish Krone</option>') ?>
<?php _e('<option value="PLN"') ?><?php if ($avishi_pp_currency == "PLN") echo " selected " ?><?php _e('>Polish Zloty</option>') ?>
<?php _e('<option value="NOK"') ?><?php if ($avishi_pp_currency == "NOK") echo " selected " ?><?php _e('>Norwegian Krone</option>') ?>
<?php _e('<option value="HUF"') ?><?php if ($avishi_pp_currency == "HUF") echo " selected " ?><?php _e('>Hungarian Forint</option>') ?>
<?php _e('<option value="CZK"') ?><?php if ($avishi_pp_currency == "CZK") echo " selected " ?><?php _e('>Czech Koruna</option>') ?>
<?php _e('<option value="ILS"') ?><?php if ($avishi_pp_currency == "ILS") echo " selected " ?><?php _e('>Israeli New Shekel</option>') ?>
<?php _e('<option value="MXN"') ?><?php if ($avishi_pp_currency == "MXN") echo " selected " ?><?php _e('>Mexican Peso	</option>') ?>
<?php _e('<option value="BRL"') ?><?php if ($avishi_pp_currency == "BRL") echo " selected " ?><?php _e('>Brazilian Real (only for Brazilian members)	</option>') ?>
<?php _e('<option value="MYR"') ?><?php if ($avishi_pp_currency == "MYR") echo " selected " ?><?php _e('>Malaysian Ringgit (only for Malaysian members)	</option>') ?>
<?php _e('<option value="PHP"') ?><?php if ($avishi_pp_currency == "PHP") echo " selected " ?><?php _e('>Philippine Peso	</option>') ?>
<?php _e('<option value="TWD"') ?><?php if ($avishi_pp_currency == "TWD") echo " selected " ?><?php _e('>New Taiwan Dollar	</option>') ?>
<?php _e('<option value="THB"') ?><?php if ($avishi_pp_currency == "THB") echo " selected " ?><?php _e('>Thai Baht	</option>') ?>
<?php _e('<option value="TRY"') ?><?php if ($avishi_pp_currency == "TRY") echo " selected " ?><?php _e('>Turkish Lira (only for Turkish members)	</option>') ?>
    </select>
  </td>
  
  
  
    
    <tr>
<td>
<strong >Select Button Type</strong>

</td>
<td></td>
<td>
<table>
<td align="center">
<?php _e('<input type="radio" name="avishi_payment_button_type" value="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif"') ?>
<?php if ($avishi_payment_button_type == "https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif") echo " checked " ?>
<?php _e('/>') ?><br /><br />
<img border="0" src="https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif" alt="" />
</td>
<td align="center">
<?php _e('<input type="radio" name="avishi_payment_button_type" value="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif"') ?>
<?php if ($avishi_payment_button_type == "https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif") echo " checked " ?>
<?php _e('/>') ?><br /><br />
<img border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" alt="" />
</td>
</table>
</td>
</tr>
 <tr><td>
    <input type="submit" name="submit" value="Save Settings" />
    </td></tr>
    </table>
    
    </form>
    
    
    
    
    
   </div>
    <?php
}

// Check for uninstall hook
if(function_exists('register_deactivation_hook'))
	register_deactivation_hook(__FILE__, 'avishi_paypal_uninstall');

// Uninstall function
function avishi_paypal_uninstall() {
	delete_option('avishi_sanbox_link');
	delete_option('avishi_live_link');
	delete_option('avishi_payment_email');
	delete_option('avishi_payment_currency'); 
	delete_option('avishi_paypal_environment'); 
    delete_option('avishi_payment_button_type');
	
}


?>