@extends('layouts.app')

@section('content')
    <div class = "uk-flex uk-flex-center">
    <div class = "uk-width-1-1@s uk-width-2-3@m uk-overflow-auto">
            <table
            class = "uk-table uk-table-divider uk-table-striped uk-table-hover
                    uk-table-middle uk-table-stacked uk-text-center">
                <thead class="uk-text-center">
                    <tr class="uk-text-center">
                        <th>کد امتحان</th>
                        <th>نام درس</th>
                        <th>شماره کلاس</th>
                        <th>زمان شروع</th>
                        <th>زمان پایان</th>
                        <th> وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($exams as $exam)
                            <tr>
                                <td>{{ $exam->id }}</td>
                                <td>{{ $exam->course->name }}</td>
                                <td>{{ $exam->room_number }}</td>
                                <td>{{ $exam->start_at }}</td>
                                <td>{{ $exam->end_at }}</td>
                                <td>
                                    @if($exam->status)
                                    <a href="{{ route('report' , ['exam_id' => $exam->id]) }}"
                                        class="uk-button uk-button-small uk-button-secondary">
                                        حضور و غیاب شده
                                    </a>
                                    @else
                                    <a href="{{ route('attendance_check' , ['exam_id' => $exam->id]) }}"
                                        class="uk-button uk-button-small uk-button-primary">
                                        شروع حضور و غیاب
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
