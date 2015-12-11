<html>
<head>
 <title>SAN</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
   <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
   <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<style type="text/css">
html,body{
background-color: #202020;

  }
.error{
margin-top: 6px;
margin-bottom: 0;
color: #000;
display: table;
padding: 5px 8px;
font-size: 11px;
font-weight: 600;
line-height: 14px;
  }

h2,h4{
  color:#ffffcc;


}  

</style>
</head>
<body>  
  <center><h2>S</h2><h4>ocial</h4><h2>A</h2><h4>wareness</h4><h2>N</h2><h4>etwork</h4></center>
      <div class="modal-dialog" style="width:40%;">
        <div class="modal-content" >
          <div class="modal-header">
            <h3 class="modal-title"><i class="icon-paragraph-justify2"></i> User Login</h3>
          </div>
           <form method="post" autocomplete="off" id="login_form">
              <div class="modal-body with-padding">                             
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Username *</label>
                      <input type="text" id="username" name="username"  class="form-control required">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Password *</label>
                      <input type="password" id="password" name="password"  class="form-control required" value="">
                    </div>
                  </div>
                </div> 
              </div>
           <div class="error" id="logerror"></div> 
            
           <!-- end Add popup  -->  
            <div class="modal-footer">
              <input type="hidden" name="id" value="" id="id">
              <button type="submit" id="btn-login" class="btn btn-primary">Submit</button>              
            </div>
          </form>          
        </div>

        <div class="col-md-8">
        <div style="font-size:13px;text-align:center;margin-top:20px">
      </div>
      </div>
      </div>   

      <div class="modal-dialog" style="width:40%;">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><i class="icon-paragraph-justify2"></i> Register</h3>
          </div>
           <form method="post" autocomplete="off" id="register_form">
              <div class="modal-body with-padding">                             
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Username *</label>
                      <input type="text" id="usernamereg" name="usernamereg"  class="form-control required">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Password *</label>
                      <input type="password" id="passwordreg" name="passwordreg"  class="form-control required" value="">
                    </div>
                  </div>
                </div> 
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Name *</label>
                      <input type="text" id="name" name="name"  class="form-control required">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-10">
                      <label>Email *</label>
                      <input type="text" id="email" name="email"  class="form-control required">
                    </div>
                  </div>
                </div>
              </div>
           <div class="error" id="regerror"></div> 
            
           <!-- end Add popup  -->  
            <div class="modal-footer">
              <input type="hidden" name="id" value="" id="id">
              <button type="submit" id="btn-register" class="btn btn-primary">Submit</button>              
            </div>
          </form>          
        </div>
        <div class="col-md-8">
        <div style="font-size:13px;text-align:center;margin-top:20px">
      </div>
      </div>
      </div> 
</body>
</html>
<script>  
  $(document).ready(function(){
    $('#login_form').validate();   
    $(document).on('click','#btn-login',function(){
      var url = "login.php";       
        if($('#login_form').valid()){
          $('#logerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
          $.ajax({
            type: "POST",
              url: url,
               data: $("#login_form").serialize(), // serializes the form's elements.
                 success: function(data)
                   {                    
                     if(data==1) {               
                       window.location.href = "profile.php";
                      } 
                     else {
                           $('#logerror').html('The email or password you entered is incorrect.');
                           $('#logerror').addClass("error");
                         }
                   }
               });
             }
        return false;
       });

    //$('#register_form').validate();   
    $(document).on('click','#btn-register',function(){
      var url = "register.php";       
        if($('#register_form').valid()){
          $('#regerror').html('<img src="ajax.gif" align="absmiddle"> Please wait...');  
          $.ajax({
            type: "POST",
              url: url,
               data: $("#register_form").serialize(), // serializes the form's elements.
                 success: function(data)
                   {                    
                     if(data==1) {               
                       window.location.href = "profile.php";
                      } 
                     else {
                        console.log(data);
                           $('#regerror').html('The email or password you entered is incorrect.');
                           $('#regerror').addClass("error");
                         }
                   }
               });
             }
        return false;
       });
});
</script>
