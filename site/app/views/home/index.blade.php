@extends('layouts.default')

@section('content')
	<header>
		{{ Form::open(array('method' => 'GET')) }}
		<div class="input-group">
			{{ Form::text('q', Input::get('q'), array('class' => 'form-control input-lg', 'placeholder' => 'Enter your search term')) }}			
			<span class="input-group-btn">
				{{ Form::submit('Search', array('class' => 'btn btn-primary btn-lg')) }}			
			</span>
		</div>
		{{ Form::close() }}
	</header>

	@if (isset($resultset))	
	<div class="results row" style="margin-top:1em;">
		<div class="col-sm-4 col-md-4 col-lg-3">
			<?php $facet = $resultset->getFacetSet()->getFacet('rating'); ?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">By MPGG Rating</h3>
				</div>
				<ul class="list-group">
					@foreach ($facet as $value => $count)
						@if ($count)
						<li class="list-group-item">
							<a href="?{{ http_build_query(array_merge(Input::all(), array('rating' => $value))) }}">{{ $value }}</a>
							<span class="badge">{{ $count }}</span>
						</li>
						@endif
					@endforeach
				</ul>    
			</div>

			<?php $facet = $resultset->getFacetSet()->getFacet('years'); ?>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">By Decade</h3>
				</div>
				<ul class="list-group">
					@foreach ($facet as $value => $count)
						@if ($count)
						<li class="list-group-item">
							<a href="?{{ http_build_query(array_merge(Input::all(), array('decade' => $value))) }}">{{ $value }}'s</a>
							<span class="badge">{{ $count }}</span>
						</li>
						@endif
					@endforeach
				</ul>    
			</div>
		</div>
		<div class="col-sm-8 col-md-8 col-lg-9">

			<header>
				<p>Your search yielded <strong>{{ $resultset->getNumFound() }}</strong> results:</p>
				<hr />
			</header>

			@foreach ($resultset as $document)

			<?php $highlightedDoc = $highlighting->getResult($document->id); ?>

			<h3>{{ (count($highlightedDoc->getField('title'))) ? implode(' ... ', $highlightedDoc->getField('title')) : $document->title }}</h3>

			<dl>
				<dt>Year</dt>
				<dd>{{ $document->year }}</dd>

				@if (is_array($document->cast))
				<dt>Cast</dt>
				<dd>{{ implode(', ', $document->cast) }}</dd>				
				@endif
			</dl>

			{{ (count($highlightedDoc->getField('synopsis'))) ? implode(' ... ', $highlightedDoc->getField('synopsis')) : $document->synopsis }}
			
    	@endforeach

		</div>
	</div>
	@endif

@endsection