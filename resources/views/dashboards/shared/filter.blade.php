<form method="GET" action="{{ route($route_name) }}" accept-charset="UTF-8"
  class="form-inline my-2 my-lg-0 float-right" role="search">
  @if (isset($select))
    <select class="custom-select form-control mr-sm-2" name="{{ $select['field_name'] }}"
      id="{{ $select['field_name'] }}">
      <option value="">{{ $select['title'] }}</option>
      @foreach ($select['data'] as $op)
        @if (gettype($op) == 'object')
          <option value="{{ $op->id }}" @if ($_GET && $op->id == $_GET[$select['field_name']]) selected="selected" @endif>{{ (isset($op->title)) ? $op->title : $op->name }}</option>
        @else
          <option value="{{ $op['id'] }}" @if ($op['id'] == $_GET[$select['field_name']]) selected="selected" @endif>{{ $op[$select['label_name']] }}</option>
        @endif
      @endforeach
    </select>
  @endif

  <div class="input-group">
    <input type="text" value="" name="date-range" class="daterange form-control">
  </div>
  <button class="btn btn-secondary form-control ml-1" type="submit">
    <i class="fa fa-search"></i>
  </button>
  {{-- @if ($_GET)
    <button class="btn btn-warning form-control ml-1" type="reset">
        <i class="fa fa-close"></i>
    </button>
  @endif --}}
</form>
<br />
<br />

<script>
  // Make datepickerrage activated.
  $(function() {
    var SelectedStart = sessionStorage.getItem("selectedStart");
    var SelectedEnd = sessionStorage.getItem("selectedEnd");
    var start = (SelectedStart == null ? moment().startOf('month') : moment(SelectedStart));
    var end = (SelectedEnd == null ? moment().endOf('month') : moment(SelectedEnd));
    $('input.daterange').daterangepicker({
      //   ranges: {
      //     'Today': [moment(), moment()],
      //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      //     'This Month': [moment().startOf('month'), moment().endOf('month')],
      //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
      //       'month')]
      //   },
      //   opens: 'left',
      autoApply: true,
      startDate: start,
      endDate: end,
    }, function(start, end, label) {
      sessionStorage.setItem('selectedStart', start);
      sessionStorage.setItem('selectedEnd', end);
    });
  });

</script>
