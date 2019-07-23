@php
@endphp
<ul class="nav nav-tabs" role="tablist">
    @foreach (['en', 'ru', 'hy'] as $lang)
        <li class="nav-item">
            <a class="nav-link{!! $loop->first?' active':null !!}" id="{!! "$id-$lang-tab" !!}" data-toggle="tab" href="#{!! "$id-$lang-pane" !!}" role="tab">{{ $lang }}</a>
        </li>
        @push('tabContent')
                <div class="tab-pane fade{!! $loop->first?' show active':null !!}" id="{!! "$id-$lang-pane" !!}" role="tabpanel">
                    <div class="form-group">
                        <label for="{!! "$id-$lang" !!}">{{ $label??null }}</label>
                        <input type="text" id="{!! "$id-$lang" !!}" name="{!! $name."[$lang]" !!}" class="form-control" placeholder="{{ $label??null }}" value="{{ $value[$lang]??null }}">
                    </div>
                </div>
        @endpush
    @endforeach
</ul>
<div class="tab-content">
    @stack('tabContent')
</div>
