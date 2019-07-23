<div class="form-group">
    <label for="{!! $id !!}">{{ $label??null }}</label>
    @if ($value)
        <div>
            <img src="{{ asset(config('banners.upload_dir').$value) }}" alt="" style="display:block; max-width:200px; width:auto; height:auto; margin:5px 0;">
        </div>
    @endif
    <input type="file" id="{!! "$id" !!}" name="{!! $name !!}" class="form-control" placeholder="{{ $label??null }}" value="{{ $value }}">
</div>