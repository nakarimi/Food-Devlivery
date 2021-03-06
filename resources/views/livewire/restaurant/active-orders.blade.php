@section('title')
    سفارشات فعال
@stop

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">سفارشات فعال</h4>
                </div>
            </div>
            <div class="card-body">
                <form  accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search..." wire:model="keyword">
                        <span class="input-group-append">
                            <button class="btn btn-secondary" type="submit">
                            <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
                <br/>
                <br/>
                <br/>

                <div class="table-responsive">
                    @include('dashboards.restaurant.orders.table')
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<!-- Add complete time for order -->
    <div class="modal custom-modal fade" id="add_order_completion_time" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>زمان تکمیل سفارش</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <form id="order_approved_form" method="POST" style="width:100%;">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    
                                    <select class="form-control" id="promissed_time" style="direction: ltr;">
                                        <option value="">00:15</option>
                                        <option value="30">00:30</option>
                                        <option value="45">00:45</option>
                                        <option value="60">01:00</option>
                                        <option value="75">01:15</option>
                                        <option value="90">01:30</option>
                                        <option value="105">01:45</option>
                                        <option value="120">02:00</option>
                                    </select>

                                    <input type="hidden" class="form-control" name="order_id" />
                                    <input type="hidden" class="form-control" name="customer_id" />
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn"  id="order_approved_form_submit_btn">تائید</a>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">لغو</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Add reject reason for order -->
    <div class="modal custom-modal fade" id="add_order_reject_reason" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>علت رد کردن سفارش</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <form id="add_reject_reason" method="POST" style="width:100%;">
                                <div class="form-group">
                                    <div class="row">
                                        {!! csrf_field() !!}
                                        <div class="col-12">
                                            <input type="hidden" class="form-control" name="order_id" />
                                            <input type="hidden" class="form-control" name="customer_id" />
                                            <a href="javascript:void(0);" data-dismiss="modal" class="form-control btn btn-primary continue-btn add_reject_reason_btn" message="نبود بعضی محتویات سفارش" >نبود بعضی محتویات سفارش</a>
                                            <a href="javascript:void(0);" data-dismiss="modal" class="form-control btn btn-primary continue-btn add_reject_reason_btn" message="عدم امکان سرویس دهی، ازدحام">عدم امکان سرویس دهی، ازدحام</a>
                                            <a href="javascript:void(0);" data-dismiss="modal" class="form-control btn btn-primary continue-btn add_reject_reason_btn" message="خارج از ساحه">خارج از ساحه</a>
                                        </div>
                                       
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Datatable JS -->
@push('scripts')
@include('dashboards.restaurant.orders.scripts')
@endpush
