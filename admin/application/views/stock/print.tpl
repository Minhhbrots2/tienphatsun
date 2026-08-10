<center>
	<div style="margin:10px 0px">
    	<a href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&booking_id={$core->encryptID($pvalTable)}" class="Quay lại trang booking">&laquo; Quay lại trang booking</a>
    </div>
</center>
<div class="container-fluid">
    <form id="newitem" method="post" action="" enctype="multipart/form-data" class="validate-form">
        <div class="row-field">
            <div class="coltrols">
            	<div class="printDIV" id="printDIV">
                	{$clsClassTable->getBookingHTML($booking_id)}
                </div>
				<div id="rendererPDF"></div>
				<div class="pull-right btn-group btn-group-sm hidden-print" style="margin-top:10px">
					<a href="javascript:void(0);" class="btn btn-default printClick"><i class="fa fa-print"></i> In ra</a>
					<a href="javascript:void(0);" class="btn btn-default cmdexportpdf"><i class="fa fa-download"></i> Tải bản PDF</a>
				</div>
            </div>
        </div>
    </form>
</div>
<center>
	<a href="{$PCMS_URL}" class="Quay lại trang quản trị">&laquo; Quay lại trang quản trị</a>
</center>
<br />
<script type="text/javascript" src="{$URL_JS}/extension/printArea/jquery.PrintArea.js"></script>
<link rel="stylesheet" type="text/css" href="{$URL_JS}/extension/printArea/PrintArea.css" />
<script type="text/javascript" src="//cdn.rawgit.com/MrRio/jsPDF/master/dist/jspdf.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/niklasvh/html2canvas/0.5.0-alpha2/dist/html2canvas.min.js"></script>
<script type="text/javascript">
	var booking_id = '{$booking_id}';
</script>
{literal}
<style type="text/css">
td{ font-size:13px !important}
.hidden { display:none !important}
</style>
<script type="text/javascript">
	$(function(){
		$('.printClick').click(function(){
			$('#printDIV').printArea();
			return false;
		});
		var pdf = $('#printDIV'),
			ww = pdf.width(),
			a4  =[ 595.28,  841.89];
		$('.cmdexportpdf').on('click',function(){
			createPDF();
		});
		function createPDF(){
			getCanvas().then(function(canvas){
				var img = canvas.toDataURL("image/png"),
				doc = new jsPDF({
				  unit:'px', 
				  format:'a4'
				});     
				doc.addImage(img, 'JPEG', 20, 20);
				doc.save('booking_'+booking_id+'.pdf');
				form.width(ww);
			});
		}
		function getCanvas(){
			pdf.width((a4[0]*1.33333) -80).css('max-width','none');
			return html2canvas(pdf,{
				imageTimeout:2000,
				removeContainer:true
			});	
		}
	});
</script>
{/literal}