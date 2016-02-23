<?php
  /**********************************************************************
  *          Session set up
  **********************************************************************/

  /* error check */
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  /* start session */
  ini_set('session.save_path', '/nfs/stak/students/m/masseyta/session');
  session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Corvallis Reuse and Repair Directory: Web Portal</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/customStylesheet.css" rel="stylesheet">
  <link href="css/media.css" rel="stylesheet">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link href='https://fonts.googleapis.com/css?family=Rubik:700' rel='stylesheet' type='text/css'>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>

/************************************************************************
*         Check Session on body load
************************************************************************/
function searchCategory(){
    $('#table').empty();
    $.ajax({type:"GET",
    url: "http://web.engr.oregonstate.edu/~masseyta/testApi" + "/index/category",
    dataType: 'json',
    success: function(data){
        var match = $('#searchName').val();
        var row = '<tr><th>' + 'Name' + '</th><th>' + 'Modify' + '</th><th>' + 'Delete' + '</th></tr>';
        for(var i = 0; i < data.length; i++){ 
         if(data[i].name == match){
            row += '<tr><td>' + data[i].name + '</td><td>' + '<input type= hidden id= edit value=' + data[i].id + '><input type= submit value=Edit id=edit onclick=editCategory()>' + '</td><td>' + '<input type= hidden id= delete value=' + data[i].id + '><input type= submit value=Delete id=del onclick=delCategory()>' + '</td></tr>';
         }
        }
        $('#table').append(row);
    },
  });
}

function delCategory(){
    var match = $('#delete').val();
    $.ajax({type:"DELETE",
    url: "http://web.engr.oregonstate.edu/~masseyta/testApi" + "/index/category/" + match,
    dataType: 'json',
    success: function(data){
    }
  });
searchCategory();
}

function editCategory(){
    var match = $('#edit').val();
    $.ajax({type:"GET",
    url: "http://web.engr.oregonstate.edu/~masseyta/testApi" + "/index/category/" + match,
    dataType: 'json',
    success: function(data){
      $('#table').empty();
      var formdata = '<div class="form-group" id="form1">';
      formdata += '<label class="control-label col-sm-2" for="text">' + 'Edit Information:' + '</label>';
      formdata += '<div class="col-sm-10">';
      formdata += '<input type ="text" class="form-control" Id="searchName" placeholder=' + 'Current:' + data[0].name + '>';
      formdata += '</div></div><p align="center"><button Id ="submit" type ="submit" class="btn btn-primary" onclick="changeCategory('+ match +'); return false" align="center">Update Category</button></p>';
      formdata += '</form>';
      $('#EditData').html(formdata);
    }
  });
}

function changeCategory(match){

  var tempname = $('#searchName').val();
  $.ajax({type:"GET",
    url: "http://web.engr.oregonstate.edu/~masseyta/testApi" + "/index/category/" + match,
    dataType: 'json',
    success: function(data){
       var tableData = "name="+tempname;
       var ident = data[0].id;
        $.ajax({type:"PUT",
            url: "http://web.engr.oregonstate.edu/~masseyta/testApi" + "/index/category/" + ident,
            data: tableData,
            success: function(){},
        });
      searchCategory();
    }
  });
}

function checkSession(){

    req = new XMLHttpRequest();
    req.onreadystatechange = function(){
     if(req.readyState == 4 && req.status == 200){

        if(req.responseText == 1){
          /* everything has passed! Yay! Go into your session */
          window.alert("You are not logged in! You will be redirected.");
          window.location.href = "http://web.engr.oregonstate.edu/~masseyta/testApi/loginPage.php";
        }
      }
    }

    /* send data to create table */
    req.open("POST","checkSession.php", true);
    req.send();
}
</script>
  </head>
  <body onload="checkSession()">

  <!-- Import Nav bar -->
  <?php include("nav.php"); ?>

  <!-- Main container -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <br></br>
        <h3>Search for a Category to Edit or Delete</h3>
        <hr></hr>
        <form class="form-horizontal" role="form">
        <div class="form-group">
           <label class="control-label col-sm-2" for="text">Category name: </label>
           <div class="col-sm-10">
            <input type ="text" class="form-control" Id="searchName" placeholder="Enter Category Name">
            </div>
        </div> <!-- end formground-->

        <p align="center">
          <button Id ="submit" type ="submit" class="btn btn-primary" onclick="searchCategory(); return false" align="center">Search for Category</button>
        </p>
        </form>

        <!-- Table is created when button is hit -->
        <div id="tableHere">
          <table class="table table-striped" id="table"></table>
        </div>
      
        <!-- edit info -->
        <div id= "EditData"></div>
    </div>
    <hr></hr>
    <!-- Hidden row for displaying login errors -->
    <div class="row">
      <div class="col-xs-12 cold-md-8" Id= "output"></div>
    </div class="row"><!-- end inner row -->
  </div> <!-- end row -->
  </div> <!-- end container-->

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  </body>
</html>