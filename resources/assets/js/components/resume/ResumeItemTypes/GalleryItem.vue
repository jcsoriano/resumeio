<template>
	<figure class="col-md-4 print-gallery-item" :id="'img-'+index+'-'+parentIndex">
		<a :href="picture" data-size="1600x1067" class="data-holder">
			<!--Card-->
			<div class="card hoverable">
				<!--Card image-->
				<div class="view overlay hm-white-slight">
					<img :src="picture" class="img-fluid" alt="">
					<a>
						<div class="mask"></div>
					</a>
				</div>
				<!--Card content-->
				<div class="card-block">
					<!--Title-->
					<h6 class="card-title" v-text="title"></h6>
				</div>
				<!--/.Card content-->
			</div>
		</a>
		<figcaption itemprop="caption description" style="display: none;" v-text="title"></figcaption>
	</figure>
</template>


<script>
	export default {
		props: {
			picture: {
				type: String,
				required: true,
			},
			title: {
				type: String,
				required: true,
			},
			index: {
				type: Number,
				required: true,
			},
			parentIndex: {
				type: Number,
				required: true,
			},
		},
		mounted() {
			let id = '#img-'+ this.index +'-'+ this.parentIndex;
			// alert(id);
			this.$nextTick(function(){
				let src = $(id).find('img').attr('src');
				// console.log(src);
				let img = new Image();
				img.onload = function () {
				  let newsize = this.width + 'x' + this.height
				  $(id).find('.data-holder').attr('data-size', newsize);
				};

				img.src = src;
			});
		},

	};
</script>