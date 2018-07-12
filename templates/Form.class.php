<?php
/**
 * Name:        Jarren Long
 * Date:        2/5/2012
 * Assignment:  PHP Articles - Support class
 * Class:       CIS 230 PHP
 * Description: This is a support class I designed to work with my website.
 *          Basically, this class is designed to handle everything
 *          form-related, so I don't have to keep reinventing the wheel
 *          with every new feature I add. Usage is simple(ish):
 *
 * 1. Create a new form ($form = new Form;)
 * 2. Open the form tag ($form->Open('formName');) method defaults to POST if not specified
 * 3. Start adding controls (see AddXXX functions below)
 * 4. Add a submit button at the bottom ($form->AddSubmit('Save Stuff');)
 * 5. Close the form ($form->Close();)
 *
 * Every time the form is submitted, all fields will be checked for
 * required data, and the contents will then be cleaned and stuffed
 * into the FormData array for later processing (using the control names
 * specified in the AddXXX() functions as array lookup keys).
 */

class Form {
  public $FormData = array();
  private $validInput = 1;
  private $method = '';
  
  //Echo a form tag
  function Open( $guid, $name, $method = 'post' ) {
    $this->method = $method;
	
    echo "<form name=\"" . $name . "_form\" action=\"" . $name .
	  ".php\" method=\"" . $method . "\">\n";
	$this->AddHidden('w', $guid);
  }
  
  //Echo a form tag
  function OpenForm( $guid, $name, $action, $method ) {
    $this->method = $method;
	
    echo "<form name=\"" . $name . "_form\" action=\"" . $action .
	  "\" method=\"" . $method . "\">\n";
	$this->AddHidden('w', $guid);
  }
  
  //Echo a form tag that supports file uploads
  function OpenUploadForm( $guid, $name, $action, $method = 'post', $max_size = -1 ) {
    $this->method = $method;
	
    echo "<form enctype=\"multipart/form-data\" name=\"" . $name . "_form\" action=\"" . $action .
	  "\" method=\"" . $method . "\">\n";
	
	$this->AddHidden('w', $guid);
	$this->AddHidden('MAX_FILE_SIZE', $max_size);
  }
  
  //Echo close form tag
  function Close() {
    echo "</form>\n\n";
  }

  /** Add different input fields to the form */
  function AddInput( $name, $label = '' ) {
    echo "  <p>" . $label;
	
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( $name ), $this->FormData[$name] );
    }
  
    echo "<input type=\"text\" name=\"" . $name .
      "\" value=\"" . $this->getVar( $name ) . "\" /></p>\n";
  }

  function AddInputWithValue( $name, $label, $value ) {
    echo "  <p>" . $label;
	
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( $name ), $this->FormData[$name] );
    }
  
    echo "<input type=\"text\" name=\"" . $name .
      "\" value=\"" . $value . "\" /></p>\n";
  }
  
  function AddTextarea( $name, $rows, $cols ) {
    echo "  <p>";
	
    // TODO: 32,000 char max!
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( $name ), $this->FormData[$name] );
    }
  
    echo "<textarea name=\"" . $name . "\" rows=\"" . $rows .
      "\" cols=\"" . $cols . "\">" . $this->getVar( $name ) .
      "</textarea></p>\n";
  }

  function AddTextareaWithValue( $name, $rows, $cols, $value ) {
    echo "  <p>";
	
    // TODO: 32,000 char max!
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( $name ), $this->FormData[$name] );
    }
  
    echo "<textarea name=\"" . $name . "\" rows=\"" . $rows .
      "\" cols=\"" . $cols . "\">" . $value .
      "</textarea></p>\n";
  }
  
  function AddUpload($label) {
    echo "  <p>" . $label;
	
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( 'upload' ), $this->FormData['upload'] );
    }
  
    echo "<input type=\"text\" name=\"upload\" value=\"" . $this->getVar( 'upload' ) . "\" /></p>\n";
  }
  
  function AddUploadWithValue($label, $value) {
    echo "  <p>" . $label;
	
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( 'upload' ), $this->FormData['upload'] );
    }
  
    echo "<input type=\"text\" name=\"upload\" value=\"" . $value . "\" /></p>\n";
  }
  
  function AddPassword( $name, $label = '' ) {
    echo "  <p>" . $label;
	
    if( $this->validInput && $this->Submitting() ) {
      //Valid data will be stuck in the FormData array, using $name for the key
      $this->validInput = $this->processForm( $this->getVar( $name ), $this->FormData[$name] );
    }
  
    echo "<input type=\"password\" name=\"" . $name .
      "\" value=\"" . $this->getVar( $name ) . "\" /></p>\n";
  }
  
  //Useful for self-postbacks
  function AddHidden( $name, $value ) {
    echo "  <p><input name=\"" . $name . "\" type=\"hidden\" value=\"" . $value . "\" /></p>\n";
  }

  //Create a submit button for the form
  function AddSubmit( $value ) {
    echo "  <p><input name=\"submit\" type=\"submit\" value=\"" . $value . "\" /></p>\n";
  }

  /** Did the form submit successfuly (with valid info)? */
  function SubmittedOK() {
    return ( $this->Submitting() && $this->validInput );
  }

  //Check if the user has submitted the form
  function Submitting() {
    //Planning ahead for GET & POST support in one ambiguous class
    if( $this->method == 'post' ) {
      return isset( $_POST['submit'] );
	} else {
	  return isset( $_GET['submit'] );
	}
  }
  
  //Return the variable stored in GET|POST (by name)
  function getVar( $name ) {
    //Planning ahead for GET & POST support in one ambiguous class
    if( $this->method == 'post' ) {
	  return $_POST[$name];
    } else {
	  return $_GET[$name];
    }
  }
  
  /**
  * Function: processForm
  * Params:   $dirty  - (in) GET|POST data to check and clean
  *           &$clean - (out) The cleaned and validated data
  *                   - Example of passing variables by reference!!
  *     See: http://php.net/manual/en/language.references.pass.php
  * Returns:  true if input data was valid and could be cleaned
  *
  * This function is being used for an initial form POST check
  * If nothing is in the form, we return false.
  * Whatever content that is present is passed through an initial
  * cleaning to strip out excess whitespace and any HTML tags before
  * being returned as the 'clean' form data.
  *
  * EDIT: Now that this function is inside the form class, the return
  *     data will be getting stored in the FormData array, using the
  *     input names as the lookup key string. Later, if I need to
  *     throw any data across pages, I can just stuff this array in
  *     a session variable and unpack it on the other side.
  */
  function processForm( $dirty, &$clean ) {
    if ( !isset( $dirty ) || $dirty == '' ) {
	  //Null or empty? Error :(
      echo "<span style=\"color: red;\">*</span>";
	  $clean = '';
      return false;
    }
  
    // Strip out all HTML tags
    $buf = strip_tags( $dirty );
    // Escape special characters, strip excess whitespace,
	// and pass back to $clean as a clean string.
    $clean = trim( htmlspecialchars( $buf ) );
  
    // We're done :)
    return true;
  }
}

?>

