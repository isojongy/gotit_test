@if(!empty($users) && count($users))
    <div class="col-md-12 text-center my-3">
        <h3 class="heading text-bold">Danh sách nhân viên</h3>
        <h6 class="text-bold text-danger">(Số điểm tối thiểu để đổi: {{ \App\Common\Constants\Common::POINT_LIMIT }})</h6>
    </div>
    @foreach($users as $user)
    <div class="col-md-4">
        <div class="card p-3 mb-2">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                    <div class="ms-2 c-details">
                        <h6 class="mb-0 text-bold">{{ $user->email }}</h6>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <h3>{{ $user->phone }}</h3>
                <h5>Số điểm: {{ $user->point }}</h5>
            </div>
            <div class="mt-2">
                <h5>Mã nhận thưởng</h5>
                {{ str_replace(',', ', ', $user->codes) }}
            </div>
            <div class="mt-5 d-flex justify-content-center">
                <button @if($user->point < \App\Common\Constants\Common::POINT_LIMIT) disabled @endif
                    data-user-id="{{ $user->id }}"
                    class="btn btn-primary mr-2 btn-redeem-point">Đổi điểm</button>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-md-12 text-center">
        <h3 class="heading">Bạn chưa có nhân viên nào.</h3>
    </div>
@endif
