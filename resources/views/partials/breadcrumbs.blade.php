<div class="row">
    <div class="col-sm-12">
        @if(isset($link))
            <div class="btn-group pull-right m-t-15">
                <a href="{{ $link['url'] }}">
                    <button type="button" class="btn btn-default waves-effect waves-light">
                        {{ $link['label'] }}
                        <span class="m-l-5"><i class="fa fa-plus"></i></span>
                    </button>
                </a>
            </div>
        @endif

        <h4 class="page-title">{{ $pageTitle }}</h4>
        <ol class="breadcrumb">
            @foreach($breadcrumbs as $breadcrumb)
                <li class="ms-hover">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                </li>
            @endforeach
            <li class="active ms-hover">
                {{ $currentSection }}
            </li>
        </ol>
    </div>
</div>