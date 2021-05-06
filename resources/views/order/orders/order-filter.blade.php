<form action="" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search" method="GET">
    <div class="input-group">
        <input type="text" class="form-control mx-1" name="code" placeholder="Code" wire:model="code">

        @if (!\Request::is('order-history')) 
            <input type="text" class="form-control" name="search" placeholder="Search..." wire:model="keyword">
        @endif

        <span class="input-group-append">
            <button class="btn btn-secondary form-control" type="submit">
                <i class="fa fa-search"></i>
            </button>

            @if (\Request::is('order-history')) 
                <input class="btn btn-primary" type="reset" value="Reset">
            @endif
        </span>
    </div>
</form>
<br/>
<br/>
<br/>