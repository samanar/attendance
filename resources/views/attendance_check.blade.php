@extends('layouts.app')

@section('content')
    <div class="uk-flex uk-flex-center">
        <div class="uk-width-2-3@m uk-width-1-1@s uk-margin">
            <h3 class="uk-text-right@m uk-text-center@s">آزمون درس {{ $exam->course->name }}</h3>
        </div>
    </div>
    <div class="uk-flex uk-flex-center">
        <div class="uk-clearfix"></div>
        <div class="uk-width-2-3@m uk-width-1-1@s">
            <table class="uk-table uk-table-divider uk-table-hover uk-table-striped uk-table-middle rtl">
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th>شماره دانشجویی</th>
                        <th>شماره صندلی</th>
                        <th>وضعیت حضور</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($finished_students as $finished_student)
                    <tr>
                        <td>{{ $finished_student->first_name }}</td>
                        <td>{{ $finished_student->last_name }}</td>
                        <td>{{ $finished_student->id }}</td>
                        <td>{{ $finished_student->pivot->chair_number }}</td>
                        <td class="{{ $finished_student->pivot->status ? 'uk-text-primary' : 'uk-text-danger' }}">
                            {{ $finished_student->pivot->status ? 'حاضر' : 'غایب' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="uk-flex uk-flex-center uk-margin-large">
        <div class="uk-card uk-card-default uk-padding uk-width-1-1@s uk-width-1-2@m">
           <div class="uk-card-media-top uk-text-center uk-align-center uk-margin">
                <img src="{{ asset('images/avatar.png') }}" alt="avatar"
                style="width: 200px; height: auto; margin: 0 auto;">
            </div>
            <div class="uk-card-body rtl">
                <div class="attendance_check_card_text">نام : {{ $next_student->first_name }}</div>
                <div class="attendance_check_card_text">نام خانوادگی : {{ $next_student->last_name }}</div>
                <div class="attendance_check_card_text">شماره دانشجویی : {{ $next_student->id }}</div>
                <div class="attendance_check_card_text">شماره صندلی : {{ $next_student->pivot->chair_number }}</div>
            </div>
            <div class="uk-card-footer uk-child-width-1-2@m uk-child-width-1-1@s rtl" uk-grid>
                <form action="{{ route('attendance_submit') }}" method="post">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $next_student->id }}">
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    <input type="hidden" name="status" value="1">
                    <button class="uk-button uk-button-primary uk-width-1-1">حاضر</button>
                </form>
                <form action="{{ route('attendance_submit') }}" method="post">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $next_student->id }}">
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    <input type="hidden" name="status" value="0">
                    <button class="uk-button uk-button-danger uk-width-1-1">غایب</button>
                </form>
            </div>
        </div>
    </div>
@endsection
