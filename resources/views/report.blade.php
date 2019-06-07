@extends('layouts.app')

@section('content')
    <a href="{{ route('exams') }}" class="uk-button uk-button-primary uk-margin uk-float-right ">بازگشت به لیست امتحان ها</a>
    <div class="uk-flex uk-flex-center uk-margin-large-top">
        <div class="uk-card uk-card-default uk-card-hover uk-card-large uk-width-2-3@m uk-width-1-1@s">
            <div class="uk-card-header uk-text-right">
                <h3 class="uk-card-title">آزمون {{ $exam->course->name }}</h3>
            </div>
            <div class="uk-card-body uk-child-width-1-2@m uk-child-width-1-1@s uk-text-center" uk-grid>
                <div>
                    کد آزمون : {{ $exam->id }}
                </div>
                <div>
                    نام درس : {{ $exam->course->name }}
                </div>
                <div>
                    کد استاد : {{ $professor->id }}
                </div>
                 <div>
                    نام استاد :‌ {{ $professor->first_name . ' ' . $professor->last_name }}
                </div>
                <div class="rtl">
                    ساعت پایان آزمون : {{ $exam->end_at }}
                </div>
                <div class="rtl">
                    ساعت شروع آزمون : {{ $exam->start_at }}
                </div>
                <div class="uk-width1-1-1">
                    کلاس : {{ $exam->room_number }}
                </div>
            </div>
        </div>
    </div>
    <div class="uk-flex uk-flex-center uk-margin">
        <div class="uk-width-2-3@m uk-width-1-1@s uk-table-responsive">
            <table class="uk-table uk-table-divider uk-table-middle uk-table-striped uk-table-hover rtl">
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>شماره دانشجویی</th>
                        <th>شماره صندلی</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->pivot->chair_number }}</td>
                        <td class="{{ $student->pivot->check == 1 ? 'uk-text-warning' : ($student->pivot->status ? 'uk-text-primary' : 'uk-text-danger') }}">
                            {{ $student->pivot->check == 1 ? 'اضافه شده' : ($student->pivot->status ? 'حاضر' : 'غایب') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
