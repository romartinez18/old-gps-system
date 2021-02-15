<script>
$(document).ready(function(e) {
	
	var oTable = $('#tabla').dataTable( {
        "oTableTools": {
            "sSwfPath": "js/copy_csv_xls_pdf.swf"
        },
	"oLanguage": {
            "sUrl": "media/spanish.txt"
        },
        "sDom": "<'row-fluid'<'span4'l><'span4 'T><'span4'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
       
    });

});
</script>
