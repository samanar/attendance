@extends('layouts.app')

@section('content')
    <div class="uk-margin uk-margin-top">
        <div class="uk-flex uk-flex-center">
            <div class="uk-card uk-card-body uk-card-default uk-card-hover uk-width-1-3@s uk-width-1-2@m">

                @if(Session::has('Error'))
                    <div class="uk-alert uk-alert-danger uk-text-center uk-width-1-1">
                        خطا در دریافت اطلاعات
                    </div>
                @endif
                <div class="uk-margin home_text_info">
                    @if( isset($req) && $req != null )
                        وضعیت : <span class="uk-text-primary">اطلاعات دریافت شده در {{ $req->date }}</span>
                    @else
                        وضعیت :‌ <span class="uk-text-danger">اطلاعات یافت نشد</span>
                    @endif
                </div>
                <a href="{{ route('data') }}" class="uk-button uk-button-secondary  uk-width-1-1">دریافت اطلاعات</a>
                <a href="{{ route('exams') }}" class="uk-button uk-button-primary uk-margin  uk-width-1-1">مشاهده لیست امتحانات</a>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script></script>
@endpush
