

<html>
<link rel="stylesheet" href="./src/css/dist/all_old.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<body>


<div id="vm">

	<form id="entryForm" onsubmit="return submitProduct(event)">
	  <div class="form-group">
	    <label for="productName">Product Name</label>
	    <input type="text" class="form-control" id="productName" name="productName">
	  </div>
	  <div class="form-group">
	    <label for="stockQty">Products in Stock:</label>
	    <input type="number" class="form-control" id="stockQty" name="stockQty">
	  </div>
	  <div class="form-group">
	    <label for="price">Price per Item:</label>
	    <input type="text" class="form-control" id="price" name="price">
	  </div>	
		<button type="submit" class="btn btn-primary mb-2" >Submit</button>
	</form>
	<table id="log" class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Product Name</th>
				<th scope="col">Product in Stock</th>
				<th scope="col">Price Per Item</th>
				<th scope="col">Time</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
	</table>

</div>
<script type="text/javascript">
	function submitProduct(e){
		var request = $.ajax({
		  url: "processRequest.php",
		  method: "POST",
		  data: { 
		  	name : $('#entryForm input:eq(0)').val(),
		  	qty:$('#entryForm input:eq(1)').val(),
		  	price:$('#entryForm input:eq(2)').val(),
		  },
		  dataType: "html"
		  ,
		  beforeSend: function() {
		  	 // setting a timeout
		    console.debug(this);
		  }

		});
		 
		request.done(function( msg ) {
		  reloadTable();
		});	
		$('#entryForm input').val("");
		$('#entryForm input:first').focus()
		e.preventDefault();		 
	}

	function calculateTotal(){
		var total=0;
		$('#log tr:not(:first,.total)').each(function(){
			let qty=$(this).find('td:eq(1)').html();
			let priceperitem=$(this).find('td:eq(2)').html();
			
			total+=parseInt(qty)*parseFloat(priceperitem);
		})
		if($('.total').length>0)
			$('.total').remove();
		var totalRow=  $("<tr>", {class: "total"});
		$(totalRow).append('<td></td>');
		$(totalRow).append('<td></td>');
		$(totalRow).append('<td></td>');
		$(totalRow).append('<td>Total</td>');
		$(totalRow).append('<td>'+total+'</td>');
		$('#log tr:last').after(totalRow);
	}
	function editRow(buton){
		$(buton).closest("tr").find('.edit').hide();
		$(buton).closest("tr").find(".editable").each(function(){
			$(this).html("<input value='"+$(this).html()+"'/>")
			}
		)
		$(buton).closest("tr").find('.save').show()
		console.debug($(buton).closest("tr").find('.save'));
	}
	function saveRow(buton){
		$(buton).closest("tr").find('.save').hide();
		var key=$(buton).closest("tr").attr("key")	
		var name=$(buton).closest("tr").find(".editable:eq(0) input").val()
		var qty=$(buton).closest("tr").find(".editable:eq(1) input").val()		
		var price=$(buton).closest("tr").find(".editable:eq(2) input").val()
		$(buton).closest("tr").find('.edit').show();
		var request = $.ajax({
		  url: "updateProduct.php",
		  method: "POST",
		  data: { 
		  	name :name,
		  	qty:qty,
		  	price:price,
		  	key:key
		  },
		  dataType: "html"		  

		});		
		request.done(function( msg ) {
		  reloadTable();
		});			
	}	
/*$(function(){
    $('#log').on('submit', function(event){
        event.preventDefault();
        alert("Form Submission stopped.");
    });
});	*/
</script>

<script>
	$( document ).ready(function() {
	    reloadTable();
	});	
	function reloadTable(){
		$('#log tr:not(:first)').remove();
		var request = $.ajax({
		  url: "getProducts.php",
		  type: "GET",
		  dataType: "json"
		  ,
		  beforeSend: function() {
		  	 // setting a timeout
		    console.debug(this);

		  },
	      success: function(data) {
	      	console.debug(data);
	      	for(var i=0;i<data.length;i++)
	      	{
	      		addtoTable(i,data[i]['Product Name'],data[i]['Products in Stock'],
	      			data[i]['Price per Item'],
	      			data[i]['Time']);
	      	}
	      }	  

		});		
	}
	function addtoTable(id,productName,qty,price,time){
		$('#log tr:last').after(
		'<tr key="'+id+'">'		
			+'<td class="editable">'+productName+'</td>'
			+'<td class="editable">'+qty+'</td>'
			+'<td class="editable">'+price+'</td>'
			+'<td>'+time+'</td>'
			+'<td><button class="edit btn-secondary btn-lg" onclick="editRow(this)">Edit</button>'
			+'<button class="save btn btn-lg btn-primary" onclick="saveRow(this)" style="display:none;">Save</button></td>'
		+'</tr>')		
	}
</script>



<div class="warning">
</div>
<div class="error">
</div>
<div class="success">
</div>
<div class="container">
</div>
</body>
</html>