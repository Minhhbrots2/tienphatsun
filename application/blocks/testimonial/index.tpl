{if $lstTestimonial[0].testimonial_id ne ''}
<section class="section testimonial" id="testimonials">
	<div class="container">
		<div class="row">
			<h2 class="section-title section-title-center mb">
				<b></b>
				<span>Ý KIẾN KHÁCH HÀNG</span>
				<b></b>
			</h2>
			<div class="col medium-12 small-12 large-12 pb-0">
				<div class="testimonials-list">
					{section name=i loop=$lstTestimonial}
					{assign var = name value = $clsTestimonial->getName($lstTestimonial[i].testimonial_id)}
					<div class="testimonial-item">
						<div class="testimonial-inner">
							<div class="thumb-info">
								<img src="{$clsTestimonial->getImage($lstTestimonial[i].testimonial_id,64,64)}" class="img-responsive" alt="{$name}" width="64px" height="64px" />
							</div>
							<div class="body">
								<div class="flex-grow content">
									<span class="quote"></span> {$clsTestimonial->getContent($lstTestimonial[i].testimonial_id)}.
								</div>
								<div class="flex-col flex-grow db-info">
									<b class="name-info block">{$name}</b>
									<span class="locaion-info block">Hà Nội</span>
								</div>
							</div>
						</div>
					</div>
					{/section}
				</div>
				{literal}
				<style type="text/css">
					.testimonial-item{
						margin-left:34px;
					}
					.testimonial-item > .testimonial-inner{
						padding:15px 15px 15px 60px;
						background:#F2F2F2;
						position:relative;
						border-radius:5px;
						-ms-border-radius:5px;
						-moz-border-radius:5px;
						-webkit-border-radius:5px;
						-khtml-border-radius:5px;
						min-height:300px;
					}
					.testimonial-inner > .thumb-info{
						position:absolute;
						left:-30px;
						top:10px;
						overflow:hidden;
						border-radius:50%;
						-ms-border-radius:50%;
						-moz-border-radius:50%;
						-webkit-border-radius:50%;
						-khtml-border-radius:50%;
						border:5px solid #F2F2F2;
					}
					.db-info > .name-info{
						font-size:14px;
					}
					.owl-controls > .owl-nav{
						display:none;
					}
					.owl-controls{
						display:flex;
						align-items:center;
					}
					.owl-dots{
						margin:15px auto;
					}
					.owl-dots > .owl-dot{
						width:12px;
						height:12px;
						border:1px solid #f44336;
						border-radius:50%;
						-ms-border-radius:50%;
						-moz-border-radius:50%;
						-webkit-border-radius:50%;
						-khtml-border-radius:50%;
					}
					.owl-dots > .owl-dot.active{
						background:#f44336;
					}
				</style>
				<script type="text/javascript">
					$(function(){
						jQuery('.testimonials-list').owlCarousel({
							items:2,
							nav:true,
							margin: 15,
							responsive:{
								0:{ items:1	},
								768:{ items:1 },
								1200:{ items:2 }
							},
							slideSpeed : 800,
							pagination: false,
							addClassActive: true,
							scrollPerPage: false,
							touchDrag: true,
						});
					});
				</script>
				{/literal}
			</div>
		</div>
	</div>
</section>
{/if}