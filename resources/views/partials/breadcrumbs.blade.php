<div class="row">
    <div class="col-md-6">
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

    <div class="col-md-6 text-right">
        @if(isset($link))
            <div class="btn-group m-t-10 m-b-10">
                <a href="{{ $link['url'] }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        {{ $link['label'] }}
                        <span class="m-l-5"><i class="fa fa-plus"></i></span>
                    </button>
                </a>
            </div>
        @endif
        @if(isset($link_2))
            <div class="btn-group m-t-10 m-b-10">
                <a href="{{ $link_2['url'] }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        {{ $link_2['label'] }}
                        <span class="m-l-5"><i class="fa fa-plus"></i></span>
                    </button>
                </a>
            </div>
        @endif
    </div>
</div>