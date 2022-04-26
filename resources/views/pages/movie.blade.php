@extends('layouts.master')
@section('meta')
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta property="og:locale" content="vi_VN">
<meta property="og:type" content="website">
<meta property="og:title" content="">
<meta property="og:description" content="">
<meta property="og:url" content="">
<meta property="og:site_name" content="">
<meta property="og:image" content="">
<title></title>
@endsection
@section('content')
<section class="movie">
	<div class="loader_home">
		<div class="inner one"></div>
		<div class="inner two"></div>
		<div class="inner three"></div>
	</div>
	<div class="box advanced">

	</div>

	<div class="box comments_hidden" style="display: none; margin-bottom: 20px">
		<div data-width="100%" class="fb-comments" data-href="{{$url}}" data-width="" data-numposts="5"></div>
	</div>
</section>
<section class="home__products" hidden>
	<div class="box">
		<div class="home__products__all" id="all__products">
			@foreach($productAll as $key)
			<div class="home__product">
				<div class="home__product__card">
					<p class="ribbon__shop">Yêu Thích +</p>
					<div class="ribbon__sale">
						<p data__price="{{$key->price}}" data__price__sale="{{$key->sale}}">10% Giảm</p>
					</div>
					<img src="{{$key->image}}" alt="" class="home__product-img">
					<img class="home__product-label_bottom" src="https://devsne.vn/source/img/a/voucher.png">
					<div class="home__product-content">
						<h3 class="home__product-title">
							{{$key->name}}
						</h3>
						<div class="home__product-bottom">
							<div class="home__product_price">
								<div class="home__product_price-default"><span>₫</span>{{number_format($key->price)}}</div>
								<div class="home__product_price-sale"><span>₫</span>{{number_format($key->sale)}}</div>
							</div>
							<div class="home__product-rating">
								<div class="number-rated">{{$key->rated}}</div>
								<div class="star-rated">
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
								</div>
								<div class="sold-qty">| Đã bán {{$key->sold}}</div>
							</div>
						</div>
					</div>
				</div>
				<?php $type = substr($key->categorie, 0, 5) ?>
				<div class="home__product__search">
					<a href="">Sản phẩm tương tự</a>
				</div>
			</div>
			@endforeach
		</div>
	</div>
</section>
<script>
	$(document).ready(function() {
		let _token = $('input[name="_token"]').val();
		$.ajax({
			url: "{{ route('movie.get-view-movie-ajax')}}",
			type: "POST",
			dataType: 'json',
			data: {
				name: "{{ $name }}",
				episode_id: "{{ $episode_id }}",
				_token: _token
			}
		}).done(function(data) {
			$('.loader_home').remove();
			// $('.box.advanced').html(data[1]);
			$('.box.advanced').html(data[1]);
			$('.comments_hidden').show();
			$('.home__products').show();

			$('[name=description]').attr('content', data[0]['introduction']);
			$('[property="og:description"]').attr('content', data[0]['introduction']);
			$('[name=keywords]').attr('content', data[2] + data[0]['name'] + ' vietsub, ' + data[0]['name'] + ' fullhd, ' + data[0]['name'] + ' fullhd vietsub, ' + data[0]['name']);
			$('title').html(data[0]['name'] + ' FullHD VietSub + Thuyết Minh');
			$('[property="og:site_name"]').attr('content', data[0]['name'] + ' FullHD VietSub + Thuyết Minh');
			$('[property="og:title"]').attr('content', data[0]['name'] + ' FullHD VietSub + Thuyết Minh');
			$('[property="og:image"]').attr('content', data[3]);


			$('.movie__media').height($('.movie__media').width() * 1080 / 1920);
			$('.movie__load').height($('.movie__media').height() + 5);

			video = videojs('video_media');
			getVideo = setInterval(restart, 1000);
			console.log(video);

			function restart() {
				if (video['cache_']['duration'] == 0 || !video['controls_'] || video['error_'] != null || isNaN(video['cache_']['duration'])) {
					let episode_id = Number($('#media').attr('id_episode'));
					let definition = $('.movie__quality').children(":selected").attr("id");
					reload(episode_id, definition);
				} else {
					$('.movie__load').hide();
					$('.movie__intro').html($('.movie__intro').html() + video['cache_']['duration']);
					console.log(video['cache_']['duration']);
					clearInterval(getVideo);
				}
			}

			function reload(episode_id, definition) {
				let _token = $('input[name="_token"]').val();
				$.ajax({
					url: "{{ route('movie.episode-ajax')}}",
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					type: "POST",
					dataType: 'json',
					data: {
						id: $('#media').attr('id_media'),
						category: $('#media').attr('category'),
						episode_id: episode_id,
						definition: definition,
						_token: _token
					}
				}).done(function(data) {
					if (video['cache_']['duration'] == 0 || !video['controls_'] || video['error_'] != null || isNaN(video['cache_']['duration'])) {
						video.src(data['mediaUrl']);
					}
					return true;
				}).fail(function(e) {
					return false;
				});
			}

			$('.tag__name').click(function() {
				$('.comments_hidden').remove();
				$('.home__products').remove();
				array['category'] = $(this).attr('id_tag');

				$('.box.advanced').html('');
				$('#preloader').show();

				let _token = $('input[name="_token"]').val();
				$.ajax({
					url: "{{ route('search_advanced') }}",
					type: "POST",
					dataType: 'json',
					data: {
						params: '',
						area: '',
						category: array['category'],
						year: '',
						_token: _token
					}
				}).done(function(data) {
					$('.box.advanced').removeClass('homepage').addClass('search_advanced_film');
					$('.box.search_advanced_film').html(data[0]);
					if (data[1] < 18) {
						$('.lds-facebook').remove();
					}
					$('#preloader').hide();
					return true;
				}).fail(function(e) {
					$('.box.advanced').removeClass('homepage').addClass('search_advanced_film');
					$('.box.search_advanced_film').html('<div style="padding-top: 30px; font-weight: 600; font-size: 20px">Không tìm thấy phim</div>');
					$('#preloader').hide();
					return false;
				});
			})
		})
		return true;
	}).fail(function(e) {
		return false;
	});
</script>
@endsection