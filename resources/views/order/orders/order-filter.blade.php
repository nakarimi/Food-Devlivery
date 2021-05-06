<form action="" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search" method="GET" id="order_filter">
    <div class="input-group">

        <input type="text" value="" name="date-range" class="daterange form-control" placeholder="Date" autocomplete="off">

        <input type="text" class="form-control mx-1" name="code" placeholder="Code" wire:model="code">

        @if (!\Request::is('order-history')) 
            <input type="text" class="form-control" name="search" placeholder="Search..." wire:model="keyword">
        @endif

        <span class="input-group-append">
            <button class="btn btn-secondary form-control" type="submit">
                <i class="fa fa-search"></i>
            </button>
            &nbsp;

            @if (\Request::is('order-history')) 
                <input class="btn btn-primary btn-sm" type="reset" id="resetbtn" value="Reset">
            @endif
        </span>
    </div>
</form>

{{-- Range Date Picker --}}
<script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/daterangepicker.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/daterangepicker.css')}}" />

<br/>
<br/>
<br/>
<script>
  // Make datepickerrage activated.
  $(function() {
    $('input.daterange').daterangepicker({
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
          'month')]
      },
      autoUpdateInput: false,
    }, function(start_date, end_date) {
        $('input.daterange').val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    });
  });

</script>