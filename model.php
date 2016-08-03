<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#detailsModel">
                  Cart modal
                </button>

                <div class="modal fade" id="detailsModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Customer Details</h4>
                      </div>
                      <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group">
                              <div class="col-sm-6">
                                <label>First name</label>
                                <input type="text" class="form-control" placeholder="First">
                              </div>
                              <div class="col-sm-6">
                                <label>Last name</label>
                                <input type="text" class="form-control" placeholder="Last">
                              </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                  <label>Phone</label>
                                  <input type="text" class="form-control" id="phone" placeholder="Phone">
                                </div>
                            </div> 
                            
                            
                            <div class="col-sm-12">
                              <h3>Pick Up Address</h3>
                              <div class="form-group">
                                  <label>Address</label>
                                  <input type="text" class="form-control" id="address" placeholder="Address"> 
                              </div>
                              <div class="form-group">
                                  <label>City</label>
                                  <input type="text" class="form-control" id="city" placeholder="City"> 
                              </div>
                              <div class="form-group">
                                  <label>State</label>
                                  <input type="text" class="form-control" id="state" placeholder="State">
                              </div>
                              <div class="form-group">
                                  <label>Country</label>
                                  <input type="text" class="form-control" id="county" placeholder="Country">
                              </div>
                              <div class="form-group">
                                  <label>Postal code</label>
                                  <input type="text" class="form-control" id="postal-code" placeholder="Postal Code">
                              </div>
                              
                             </div>   


                            
                            
                            <div class="col-sm-12">
                              <h3>Drop off Address</h3>
                              <div class="form-group">
                                  <label>Address</label>
                                  <input type="text" class="form-control" id="address" placeholder="Address"> 
                              </div>
                              <div class="form-group">
                                  <label>City</label>
                                  <input type="text" class="form-control" id="city" placeholder="City"> 
                              </div>
                              <div class="form-group">
                                  <label>State</label>
                                  <input type="text" class="form-control" id="state" placeholder="State">
                              </div>
                              <div class="form-group">
                                  <label>Country</label>
                                  <input type="text" class="form-control" id="county" placeholder="Country">
                              </div>
                              <div class="form-group">
                                  <label>Postal code</label>
                                  <input type="text" class="form-control" id="postal-code" placeholder="Postal Code">
                              </div>
                              
                            </div>

                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Proceed to Checkout</button>
                      </div>
                    </div>
                  </div>
                </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>