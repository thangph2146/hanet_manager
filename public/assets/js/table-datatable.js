$(function() {
	"use strict";
	
	
	    $(document).ready(function() {
			$('#example').DataTable();
		  } );
		  
		  $(document).ready(function() {
			var table = $('#example2').DataTable( {
				responsive: {
					details: {
						display: $.fn.dataTable.Responsive.display.modal( {
							header: function ( row ) {
								var data = row.data();
								return 'Details for '+data[0]+' '+data[1];
							}
						} ),
						renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
							tableClass: 'table'
						} )
					}
				},
				lengthChange: false,
				paging: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print'],
				oLanguage: {
					"sInfo" : "Hiện có _TOTAL_ item",// text you want show for info section
				},
			} );

			  table.buttons().container()
			  .appendTo( '#example2_wrapper .col-md-6:eq(0)' );

			  $('#select-all').on('click', function(){

				  var rows = table.rows({ 'search': 'applied' }).nodes();
				  $('input[type="checkbox"]', rows).prop('checked', this.checked);
			  });

			  $('#table tbody').on('change', 'input[type="checkbox"]', function(){

				  if(!this.checked){
					  var el = $('#select-all').get(0);
					  if(el && el.checked && ('indeterminate' in el)){
						  el.indeterminate = true;
					  }
				  }
			  });

		} );

	});
