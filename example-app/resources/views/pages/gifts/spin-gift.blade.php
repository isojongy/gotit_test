
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!--Section: Block Content-->
        <section>
            <!-- Button trigger modal -->
            <form>
                <div class="form-group">
                    <label>Mã dự thưởng*</label>
                    <input required class="form-control" type="text" maxlength="3" id="code" name="code" />
                </div>
                <button id="btn-submit" type="button" class="btn btn-primary">
                    Quay
                </button>
            </form>


            <!-- Modal -->
            <div class="modal fade right" id="modalGift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="true">
            <div class="modal-dialog modal-side modal-bottom-right " role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pl-0 text-uppercase text-bold">Xin chúc mừng</h5>
                    <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row my-3">
                        <div class="col-12 text-center">
                            <img class="gift-img" src="{{ asset('img/balo.jpeg') }}" alt="gift" />
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-12 text-center">
                            <p>Bạn đã nhận được</p>
                            <h2><strong class="gift-name">Balo</strong></h2>
                            <h2 class="mb-0"><span class="badge badge-light">Chúng tôi sẽ liên hệ với bạn trong vòng 3 ngày <br/> để xác nhận thông tin giao quà tặng.</span></h2>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-primary mr-2" data-dismiss="modal">OK</button>
                </div>
                </div>
            </div>
            </div>
        </section>
        <!--Section: Block Content-->
    </div>
</div>
@endsection
