<?php 
function send_email($to, $message_cont, $type=null)
{ 
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$message ='<p><span style="font-family: Arial,Helvetica,sans-serif;"> </span></p>
<table style="border-collapse: collapse; border-spacing: 0; border: 1px solid #a6ce45; background-color: #fff; padding: 0;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr style="height: 36px;" height="36">
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="middle"><span style="background-color: #a6ce45;"> </span></td>
<td style="width: 500px; background-color: #a6ce45;" width="500" valign="middle"><span style="background-color: #a6ce45;"><span style="color: white; font-size: small;"><strong> Shopify Store Finder - '. $message_cont["msg_head"]. '</strong></span></span><span style="color: #ffffff; font-size: small;"><strong> </strong></span><br /></td>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="middle"><span style="background-color: #a6ce45;"> </span></td>
</tr>
<tr>
<td style="height: 20px;" colspan="3" valign="top">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 30px;" width="30" valign="top"><br /></td>
<td style="width: 500px;" width="500" valign="top"><span style="font-family: Arial,Helvetica,sans-serif;"> 
<table style="border-collapse: collapse; border-spacing: 0; padding: 0;" border="0" cellspacing="0" cellpadding="0" width="499" height="108" align="left">
<tbody>
<tr>
<td valign="top"></td>
</tr>
<tr>
<td valign="top"><br /></td>
</tr>
<tr>
<td valign="top">'. $message_cont["msg_body_line_1"]. '	</td>
</tr>
<tr>
<td style="height: 25px;" valign="top">

<p>'. $message_cont["msg_body_line_2"]. ' <br />

 </p>
<br />


</td>
</tr>
<tr>
<td valign="top">
' ;
if($type == "invoice") {
	$message .= '<div class="ln_solid"></div>
<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Order summary</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="order_invoice" class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><strong>Product Name</strong></th>
                                    <th class="text-center"><strong>Product Price</strong></th>
                                    <th class="text-center"><strong>Product Quantity</strong></th>
                                    <th class="text-right"><strong>Total</strong></th>
                                </tr>
                            </thead>
                            <tbody>';
                            foreach($message_cont["msg_body_line_3"]['product'] as $key)
									{
							$message .='<tr> 
							<td align="">'.$key["title"].'</td>
							<td align="center">$'.$key["price"].'</td>
							<td align="center">'.$key["quantity"].'</td>
							<td align="center">$'.$key["total"].'</td>
							 </tr>';

									
									}
													
							$message .='
                                								</tbody>
								<tfoot>
                                 <tr>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-center"><strong>Sub total</strong></td>
                                    <td class="emptyrow text-right">$'.$message_cont["msg_body_line_3"]["subtotal_price"].'</td>
                                </tr> 
                                   <tr>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-center"><strong>Total tax</strong></td>
                                    <td class="emptyrow text-right">$'.$message_cont["msg_body_line_3"]["total_tax"].'</td>
                                </tr> 
                                <tr>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow"></td>
                                    <td class="emptyrow text-center"><strong>Total</strong></td>
                                    <td class="emptyrow text-right">$'.$message_cont["msg_body_line_3"]["total_price"].'</td>
                                </tr> 
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>';} 
    else if($type == "product_picked_admin") {
	$message .= '<div class="ln_solid"></div>
<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Products details</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="product_details" class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><strong>Product Name</strong></th>
                                    <th class="text-center"><strong>Store Name</strong></th>
                                    <th class="text-center"><strong>Product Quantity</strong></th>
                                
                                </tr>
                            </thead>
                            <tbody>';
                            foreach($message_cont["msg_body_line_3"] as $key)
									{
							$message .='<tr> 
							<td align="">'.$key["product_name"].'</td>
							<td align="center">'.$key["store_name"].'</td>
							<td align="center">'.$key["product_quantity"].'</td>
							 </tr>';

									
									}
													
							$message .='</tbody>
								<tfoot>
                              
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>';} 
   else if($type == "product_picked_customer") {
	$message .= '<div class="ln_solid"></div>
<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Products details</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="product_details" class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><strong>Product Name</strong></th>
                                    <th class="text-center"><strong>Store Name</strong></th>
                                    <th class="text-center"><strong>Product Quantity</strong></th>
                                
                                </tr>
                            </thead>
                            <tbody>';
                            foreach($message_cont["msg_body_line_3"] as $key)
									{
							$message .='<tr> 
							<td align="">'.$key["product_name"].'</td>
							<td align="center">'.$key["store_name"].'</td>
							<td align="center">'.$key["product_quantity"].'</td>
							 </tr>';

									
									}
													
							$message .='</tbody>
								<tfoot>
                              
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>';} 
    
    else if($type == "product_picked_driver") {
	$message .= '<div class="ln_solid"></div>
<div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center"><strong>Products details</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="product_details" class="table table-condensed">
                            <thead>
                                <tr>
                                    <th><strong>Product Name</strong></th>
                                    <th class="text-center"><strong>Store Name</strong></th>
                                    <th class="text-center"><strong>Product Quantity</strong></th>
                                
                                </tr>
                            </thead>
                            <tbody>';
                            foreach($message_cont["msg_body_line_3"] as $key)
									{
							$message .='<tr> 
							<td align="">'.$key["product_name"].'</td>
							<td align="center">'.$key["store_name"].'</td>
							<td align="center">'.$key["product_quantity"].'</td>
							 </tr>';

									
									}
													
							$message .='</tbody>
								<tfoot>
                              
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>';}
else
{
	$message .= '</br>';
}
$message .='
</td>
</tr>

<tr>
<td valign="top">
<p>If you have any questions please contact us at <a href="mailto:info@shopifystorefinder.com">info@shopifystorefinder.com</a></p>
</td>
</tr>
</tbody>
</table>
</span></td>
<td style="width: 30px;" width="30" valign="top"><br /></td>
</tr>
<tr>
<td style="height: 20px;" colspan="3" valign="top"><br /></td>
</tr>
<tr>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="top"><br /></td>
<td style="background-color: #a6ce45; padding: 15px 0;" valign="top"><span style="background-color: #a6ce45;"><span style="font-family: Arial,Helvetica,sans-serif;"> 
<table style="border-collapse: collapse; border-spacing: 0; text-align: justify; padding: 0;" border="0" cellspacing="0" cellpadding="0" width="500" height="128" align="left">
<tbody>
<tr>
<td style="width: 500px; height: 20px;" width="500" valign="top">
<p style="font-weight: bold;">Regards,<br /><strong> </strong></p>
<p style="font-weight: bold;">Team Shopify Store Finder</p>
</td>
</tr>
<tr>
<td>
<p><span style="font-family: Arial; font-size: xx-small;">Notice:  The information in this email and in any  attachments is confidential  and intended solely for the attention and use of the  named addressee.  This information may be subject to legal professional or other   privilege or may otherwise be protected by work product immunity or  other legal  rules. It must not be disclosed to any person without  authorization. If you are  not the intended recipient, or a person  responsible for delivering it to the  intended recipient, you are not  authorized to and must not disclose, copy,  distribute, or retain this  message or any part of  it.</span></p>
</td>
</tr>
</tbody>
</table>
</span> </span></td>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="top"><span style="background-color: #a6ce45;"> </span></td>
</tr>
</tbody>
</table>
<p><span style="font-family: Arial,Helvetica,sans-serif;"> </span></p>';
// echo $to;
echo $message;
    if(mail($to,$message_cont["subject"],$message,$headers))
          {
           // echo $message;
          }
          else
          {
            echo 'error while sending email';
          }
    
}
?>