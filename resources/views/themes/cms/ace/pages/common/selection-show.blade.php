@php $default_page_size = (isset($default_page_size) && $default_page_size > 0) ? $default_page_size : 10; @endphp
@php $has_choose = isset($has_choose) ? $has_choose : false; @endphp
<select class="form-control custom-select" name="page_size" id="page_size">
    @if ($has_choose)
    <option value="-1">{{ trans('fotober.common.show') }}</option>
    @endif
    @foreach(getPageSizeShow() as $key_page_size => $item_page_size)
        <option value="{{ $key_page_size }}" {{ ($key_page_size == $default_page_size) ? 'selected' : '' }}>
            {{ $item_page_size }}
        </option>
    @endforeach
</select>
