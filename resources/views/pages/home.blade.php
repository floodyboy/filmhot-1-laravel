@extends('layouts.master')
@section('content')
<section class="home">
	<div class="container"></div>
</section>
<div class="container homepage" id="1"> 
	<div class="w-full pb-[10px] flex justify-between mt-20">
		<div class="w-9/12 listfilm">
			@foreach($movie_home['recommendItems'] as $key => $recommendItems)
			@if($recommendItems['homeSectionType'] == 'SINGLE_ALBUM')
			<div class="mb-8">
				<div class="flex items-center justify-between mb-4">
					<div class="flex items-center gap-2 text-[24px] font-semibold">
						<span>{{$recommendItems['homeSectionName']}}</span>
					</div>
					<div class="">
						<a href="">
							<button class="flex items-center gap-1 text-[16px] font-medium text-white rounded-[10px] px-2 py-1">
								<h1> Xem thêm </h1>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
								</svg>
							</button>
						</a>
					</div>
				</div>
				<div class="grid grid-cols-6 gap-4">
					@foreach($recommendItems['recommendContentVOList'] as $key => $movie)
					<a href="movies/category={{$movie['category']}}&id={{$movie['id']}}" class="bg-[#27282d] rounded-xl card__film"> 
						<?php 
						$urlImage = 'img/'.$movie['category'].$movie['id'].'.jpg';
						if(!file_exists($urlImage)) {
							$urlImage = $movie['imageUrl'];
						}
						?>
						<img class="object-cover w-full rounded-t-xl"
						src="{{$urlImage}}" alt="image" />
						<div class="mx-4 text-center">
							<p class="text-gray-100 py-1 text-[14px] film__name">{{$movie['title']}}</p>
						</div>
					</a>
					@endforeach
				</div>
			</div>
			@endif	
			@endforeach
		</div>
		<div class="w-3/12 px-[30px]">
			<div class="text-[24px] font-semibold mb-4">Top tìm kiếm</div>
			<?php $image = Session('image')?Session::get('image'):[]; ?>
			@foreach($top_search['list'] as $movie)
			<a href="movies/category={{$movie['domainType']}}&id={{$movie['id']}}" class="flex mb-6 card__film">
				<?php 
				$urlImage = 'img/'.$movie['domainType'].$movie['id'].'top_search.jpg';
				if(!file_exists($urlImage)) {
					$urlImage = $movie['cover'];
					$image[$movie['domainType'].$movie['id'].'top_search'] = $movie['cover'];	
				} 
				?>
				<img src="{{$urlImage}}" class="w-7/12 rounded-[10px]">
				<div class="w-5/12 ml-[5px]">{{$movie['title']}}</div>
			</a>
			@endforeach
			<?php Session()->put('image', $image); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	var scroll = true;
	$(window).scroll(function() {
		value =  $('header').height() + $(".homepage").height() - $(window).scrollTop() - $(window).height() - 20;
		if(value < 0 && scroll) {
			scroll = false;
			let _token = $('input[name="_token"]').val();
			$.ajax({
				url: 'home-ajax',
				type: "POST",
				dataType: 'json',
				data: {
					page: $('.homepage').attr('id'),
					_token: _token
				}
			}).done(function (data) {
				$('.listfilm').html($('.listfilm').html() + data[0]);
				$('.homepage').attr('id', data[1]);
				
				setTimeout(function() {
					scroll = true;
				},2000);

				return true;
			}).fail(function (e) {
				return false;
			});
		}
	});
</script>
@endsection