<?php
/**
 * Name:        Jarren Long
 * Date:        2/27/2012
 * Assignment:  PHP Products
 * Class:       CIS 230 PHP
 * Description: Products page template for Long Technical, Co. This script
 *          handles the creation, viewing, and moderation of all products
 *          managed on the site, as well as their attached reviews.
 */

include('comments.php');

class Products extends Widget {
  
  function FormatItem($assoc_array, $db_fields) {
    global $user;
	global $comments;
	
    echo "  <div class=\"product_box\">
      <h2>" . $this->LinkToAction($user, 'show', $assoc_array[$db_fields[1]], array($this->order_by_field=>$assoc_array[$this->order_by_field])) . "</h2>
      <p>" . $assoc_array[$db_fields[2]] . "</p>
	  <a href=\"." . $assoc_array[$db_fields[6]] . "\" " . /* rel=\"lightbox\" */ "title=\"\$" .
	      $assoc_array[$db_fields[4]] . ' - ' . $assoc_array[$db_fields[2]] . "\">";
	echo "\n    <img class=\"product_box_img\" src=\"." . $assoc_array[$db_fields[6]] . "\" alt=\"" . $assoc_array[$db_fields[1]] . "\" />\n  </a>\n
      <p>Price: \$<b>" . $assoc_array[$db_fields[4]] . "</b> In Stock: <b>" . $assoc_array[$db_fields[3]] . "</b></p>\n";

	echo $comments->GetAverageRating(Widget::getName(), $assoc_array[$db_fields[0]]);
		
	if((isset($user) && $user->LoggedIn()) || $user->GetGroupId() == GROUP_ADMIN) {
      echo "  <br/>" . $this->BuildModLinks($user, $assoc_array["id"]);
	}
	
    echo "</div>\n\n";
	
    if(Widget::getLastAction() == 'show') {
	  echo "<div class=\"box\" style=\"width: 72%; float: right;\">";
	  $comments->Process(array('id'=>$assoc_array[$db_fields[0]]));
	  echo "</div>\n\n";
	}
  }

  function action_new($param = '') {
    $cn = Widget::getName();
    // User requested to add a new item
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $this->guid, $cn, $cn . '.php', 'get' );
    $this->form->AddInput( 'str_name', "Name: " );
    $this->form->AddTextarea( 'str_description', '12', '40' );
	$this->form->AddInput( 'str_qty', "Quantity: " );
	$this->form->AddInput( 'str_price', "Price: " );
	$this->form->AddInput( 'str_cost', "Cost: " );
	$this->form->AddInput( 'str_image_path', "Image: " );
	$this->form->AddHidden( 'opt', 'save' );
    $this->form->AddSubmit( "Save" );
    $this->form->Close();
  }
  
  function action_edit($param = '') {
    global $db;
	$cn = Widget::getName();
	
    // User requested to edit an existing article, fetch it
	$results = $db->Query( 'SELECT * FROM products WHERE id=' . $param['id'] );
  
    $row = mysqli_fetch_assoc( $results );
    // Build and display the form, validate inputs, etc.
    $this->form->OpenForm( $this->guid, $cn, $cn . '.php', 'get' );
	$this->form->AddInputWithValue( 'str_name', "Name: ", $row['name'] );
    $this->form->AddTextareaWithValue( 'str_description', '12', '40', $row['description'] );
	$this->form->AddInputWithValue( 'str_qty', "Quantity: ", $row['qty'] );
	$this->form->AddInputWithValue( 'str_price', "Price: ", $row['price']);
	$this->form->AddInputWithValue( 'str_cost', "Cost: ", $row['cost'] );
	$this->form->AddInputWithValue( 'str_image_path', "Image: ", $row['image_path']  );
	$this->form->AddHidden( 'opt', 'update' );
	$this->form->AddHidden( 'id', $param['id'] );
    $this->form->AddSubmit( "Update" );
    $this->form->Close();
  }
  
  function action_update($param = '') {
    global $db;
  
    if( $this->form->SubmittedOK() ) {
      // User is updating an article
	  $up  = 'name=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_name']) . '\'';
	  $up .=', description=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_description']) . '\'';
	  $up .=', qty=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_qty']) . '\'';
	  $up .=', price=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_price']) . '\'';
	  $up .=', cost=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_cost']) . '\'';
	  $up .=', image_path=\'' . mysqli_real_escape_string($db->getHandle(), $param['str_image_path']) . '\'';
	
      if( $db->Update( 'products', $param['id'], $up ) ) {
	    $db->Close();
        // Redirect back to here to show the new post
        header( 'Location: products.php' );
      } else {
        echo "Error updating product!\n";
      }
    }
  }
  
}

?>
