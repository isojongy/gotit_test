
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @if(!empty($gifts) && count($gifts))
            <div class="col-md-12 text-center my-3">
                <h3 class="heading text-bold">Danh sách giải thưởng</h3>
            </div>
            @foreach($gifts as $gift)
            <div class="col-md-4">
                <div class="card gift-card p-3 mb-2">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex flex-row align-items-center">
                            <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                            <div class="ms-2 c-details">
                                <h6 class="mb-0 text-bold">{{ $gift->name }}</h6>
                            </div>
                        </div>
                        <div class="badge"> <span>{{ \Carbon\Carbon::parse($gift->created_at)->format('d/m/Y H:i:s') }}</span> </div>
                    </div>
                    <div class="mt-5">
                        <img class="gift-img" src="{{ asset($gift->img_src) }}" alt="gift" />
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-md-12 text-center">
                <h3 class="heading">Bạn chưa có giải thưởng nào.</h3>
            </div>
        @endif
    </div>
</div>
@endsection
