// $.validator.setDefaults( {
// 	submitHandler: function () {
// 		alert( "submitted!" );
// 	}
// });

$( document ).ready( function () {
	// User form
	$( "#userForm" ).validate( {
		rules: {
			name: {
				required: true,
				maxlength: 99
			},
			password: {
				required: true,
				minlength: 8
			},
			roles: "required",
			email1: {
				required: true,
				email: true,
				maxlength: 99
			},
			password_confirmation: {
				required: true,
				minlength: 8,
				equalTo: "#password"
			},
			emp_id: {
				required: true,
			}
		},
		messages: {
			name: {
				required: "Please enter a name",
				maxlength: "Your name must not exceed of 99 characters"
			},
			email: {
				required: "Please enter an email",
				maxlength: "Your email must not exceed of 99 characters"
			},
			roles: "Please select a role",
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 8 characters long"
			},
			password_confirmation: {
				required: "Please provide a password",
				minlength: "Your password must be at least 8 characters long",
				equalTo: "Please enter the same password as above"
			},
			emp_id: "Please select an Empployee"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			// Add `has-feedback` class to the parent div.form-group
			// in order to add icons to inputs
			element.parents( ".col-sm-5" ).addClass( "has-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}

			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !element.next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
			}
		},
		success: function ( label, element ) {
			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !$( element ).next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
		}
	});

	// User Edit form
	$( "#userEditForm" ).validate( {
		rules: {
			name: {
				required: true,
				maxlength: 99
			},
			roles: "required",
			email1: {
				required: true,
				email: true,
				maxlength: 99
			},
			status: "required"
		},
		messages: {
			name: {
				required: "Please enter a name",
				maxlength: "Your name must not exceed of 99 characters"
			},
			email: {
				required: "Please enter an email",
				maxlength: "Your email must not exceed of 99 characters"
			},
			roles: "Please select a role"
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			// Add `has-feedback` class to the parent div.form-group
			// in order to add icons to inputs
			element.parents( ".col-sm-5" ).addClass( "has-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}

			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !element.next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
			}
		},
		success: function ( label, element ) {
			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !$( element ).next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
		}
	});	
	
	$( "#employeeCreateForm" ).validate( {
		rules: {
			first_name: {
				required: true,
				maxlength: 75
			},
			last_name: {
				required: true,
				maxlength: 45
			},
			father_name: {
				required: true,
				maxlength: 110
			},
			mother_name: {
				required: true,
				maxlength: 110
			},
			date_of_birth: {
				required: true
			},
			emp_birth_reg_no: {
				maxlength: 20
			},
			emp_nid: {
				maxlength: 12
			},
			present_address: {
				required: true,
				maxlength: 250
			},
			permanaunt_address: {
				required: true,
				maxlength: 250
			},
			emp_mobile_no: {
				required: true,
				maxlength: 14
			},
			last_edu_certificate: {
				required: true,
				maxlength: 100
			},
			emp_experiance_details: {
				required: true,
				maxlength: 250
			},
			emp_contact_person_name: {
				required: true,
				maxlength: 75
			},
			emp_contact_person_mobile: {
				required: true,
				maxlength: 14
			},
			joining_date: {
				required: true
			},
			emp_remark: {
				maxlength: 250
			},
			emp_status: {
				required: true
			}
		},
		messages: {			
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			// Add `has-feedback` class to the parent div.form-group
			// in order to add icons to inputs
			element.parents( ".col-sm-5" ).addClass( "has-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}

			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !element.next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
			}
		},
		success: function ( label, element ) {
			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !$( element ).next( "span" )[ 0 ] ) {
				$( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
		}
	});

} );