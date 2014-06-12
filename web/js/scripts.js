//$(".alert").addClass("in").fadeOut(4500);

/* swap open/close side menu icons */
$('[data-toggle=collapse]').click(function(){
  	// toggle icon
  	$(this).find("i").toggleClass("glyphicon-chevron-right glyphicon-chevron-down");
});

$(document).ready(function() {
	$('#addContact').on('click', function() {
		$('#contact-container').append($('#contact-tpl').html());
	});

	$('#addFirearm').on('click', function() {
		$('#firearm-container').append($('#firearm-tpl').html());
		tagReadyFireArm($('input' ,$('#firearm-container').children().last()));
	});

	$('#addEquipment').on('click', function() {
		$('#equipment-container').append($('#equipment-tpl').html());
	});

	$('#addSalary').on('click', function() {
		$('#salary-container').append(template(
			$('#salary-tpl').html(),
			{ index: $('#salary-container .salary-row').length }
		));

		tagReadyStaff($('input.typeahead-staff' ,$('#salary-container').children().last()));
	});

	//js for client form
	if($('html.client-form').length) {
		clientForm();
	}

  if($('html.client-list').length) {
    clientList();
  }
});

var firearms;
var staff;
var billingId;

var createBilling = function(id) {
  billingId = id;
  $('#myModal').modal();
};

var clientList = function() {
  $('#start').datepicker({
      format: "yyyy-mm-dd",
      weekStart: 0,
      startView: 1
  });

  $('#end').datepicker({
      format: "yyyy-mm-dd",
      weekStart: 0,
      startView: 1
  });

  $("#generate").click(function() {
    var url = '/billing/'+billingId+'?start='+$("#start").val()+'&end='+$("#end").val();
    $.get(url, function(html) {
      var WindowObject = window.open("", "PrintWindow",
      "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
      WindowObject.document.writeln(html);
      WindowObject.document.close();
      WindowObject.focus();
      WindowObject.print();
    });
  });
}

var clientForm = function() {
	firearms = new Bloodhound({
		datumTokenizer: function(s) {

		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		limit: 10,
		remote: {
			url: '/lists/firearms?ajax&query=%QUERY',
			filter: function(list) {
				return $.map(list, function(firearm) {
					firearm['tokens'] = [firearm.firearm_title, firearm.firearm_serial];
					return firearm;
				});
			}
		}
	});

	firearms.initialize();
	var el = $('.typeahead-firearms');
	tagReadyFireArm(el);

	staff = new Bloodhound({
		datumTokenizer: function(s) {

		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		limit: 10,
		remote: {
			url: '/lists/staff?ajax&query=%QUERY',
			filter: function(list) {
				return $.map(list, function(user) {
					user['user_fullname'] = user.user_firstname+' '+user.user_lastname;
					user['tokens'] = [user.user_firstname, user.user_lastname];
					return user;
				});
			}
		}
	});

	staff.initialize();
	var el = $('.typeahead-staff');
	tagReadyStaff(el);

	$('body').on('keypress', '.contact.salary', function(e) {
		if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
			return e.preventDefault();
		}
	});

	$('body').on('keyup', '.contact.salary', function() {
		var inputs = $('.contact.salary');
		var sum = 0;
		for(var i=0; i<inputs.length; i++) {
			sum+=parseFloat($(inputs[i]).val());
		}

		$('.total-value').text(sum.formatMoney(2)+'Php');
	});

	$('body').on('focusout', '.tt-input', function() {
		$('.tt-input').val('');
	});
}

var tagReadyStaff = function(el) {
	el.tagsinput({
		maxTags: 1,
		freeInput: false,
		itemValue: 'value',
		itemText: 'text'
	});
	el.tagsinput('input').typeahead(null, {
			name: 'staff',
			displayKey: 'user_fullname',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: staff.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile('{{user_lastname}}, {{user_firstname}}')
			}
		}
	).bind('typeahead:selected', $.proxy(function (obj, datum) {
		this.tagsinput('add', { value: datum.user_id, text:datum.user_fullname });
		$('.tt-input').val('');
	}, el));

	return el;
}

var tagReadyFireArm = function(el) {
	el.tagsinput({
		freeInput: false,
		itemValue: 'value',
		itemText: 'text'
	});
	el.tagsinput('input').typeahead(null, {
			name: 'firearms',
			displayKey: 'firearm_serial',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: firearms.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile('{{firearm_title}} ({{firearm_serial}})')
			}
		}
	).bind('typeahead:selected', $.proxy(function (obj, datum) {
		this.tagsinput('add', { value: datum.firearm_id, text:datum.firearm_serial });
		$('.tt-input').val('');
	}, el));

	return el;
}

var template = function(templateHTML, data) {
	var index = '';
	for(var x in data) {
		index = x;
		if(x.substring(0,1)=='_') {
			index = x.substring(1);
		}

		templateHTML = templateHTML.replace(new RegExp('{{'+index.toUpperCase()+'}}', 'g'), data[x]);
	}

	return templateHTML;
};

Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
