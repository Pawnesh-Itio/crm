// JavaScript Document

// For Add / Remove Signature
$('#toggleSignature').change(function() { 
  // Get current content
  
  //alert(content);
  //alert(signature);
     if (this.checked) { 
	 let content = $('.editor').val() || $('.editor').html();
	 //alert("Add");
	 		// Append signature (if not already present)
			if (!content.includes(signature)) {
				content += signature;
			}
			// Set content back
			$('.editor').jqteVal(content);    // for <div contenteditable> or WYSIWYG
			$('.editor').val(content);     // for <textarea>
			
	 }else{ 
	 //alert("Remove");
	 let content = $('.editor').val() || $('.editor').html();
			// Remove signature
			content = content.replace(signature, '').trim();
			
			// Set content back
			$('.editor').jqteVal(content);    // for <div contenteditable> or WYSIWYG
			$('.editor').val(content);     // for <textarea>

	 }
  });

// End Add / Remove Signature