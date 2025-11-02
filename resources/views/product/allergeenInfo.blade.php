@extends('layouts.app')

@if ($toonFallback)
	@push('head')
		<meta http-equiv="refresh" content="4;url={{ route('product.index') }}">
	@endpush
@endif

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-10">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h1 class="mb-0">{{ $title }}</h1>
				<a href="{{ route('product.index') }}" class="btn btn-link">&laquo; Terug naar overzicht</a>
			</div>

			<div class="card mb-4">
				<div class="card-body">
					<h2 class="h5 mb-3">Productinformatie</h2>
					<dl class="row mb-0">
						<dt class="col-sm-3">Naam product</dt>
						<dd class="col-sm-9">{{ $product->Naam ?? 'Onbekend' }}</dd>
						<dt class="col-sm-3">Barcode</dt>
						<dd class="col-sm-9">{{ $product->Barcode ?? 'Onbekend' }}</dd>
					</dl>
				</div>
			</div>

			<div class="card shadow-sm">
				<div class="card-body">
					<h2 class="h5 mb-3">Allergenen</h2>
					<table class="table table-striped align-middle">
						<thead class="table-light">
							<tr>
								<th scope="col">Naam</th>
								<th scope="col">Omschrijving</th>
							</tr>
						</thead>
						<tbody>
							@if ($toonFallback)
								<tr>
									<td colspan="2" class="text-center fw-semibold">
										In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken.
									</td>
								</tr>
							@elseif ($allergenen->isEmpty())
								<tr>
									<td colspan="2" class="text-center">Er zijn geen allergenen geregistreerd.</td>
								</tr>
							@else
								@foreach ($allergenen as $allergeen)
									<tr>
										<td>{{ $allergeen->AllergeenNaam }}</td>
										<td>{{ $allergeen->AllergeenOmschrijving }}</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection