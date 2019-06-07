@extends('layouts.app')


@section('content')
<div class="uk-flex uk-flex-center uk-child-width-2-3@m, uk-child-width-1-1@s" uk-grid>
        <div class="uk-margin">
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
        <div class="uk-margin">
            <button class="uk-button uk-width-1-1 uk-button-primary" uk-toggle="target: #add_new_student" type="button">افزودن دانشجوی جدید</button>
            <button class="uk-button uk-width-1-1 uk-button-danger uk-margin" uk-toggle="target: #get_signature">پایان حضور و غیاب</button>
        </div>

        <div id="add_new_student" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title uk-text-right">افزودن دانشجوی جدید</h2>
                <form
                action="{{ route('add_exam_student') }}"
                method="post"
                class="uk-form-stacked rtl uk-clearfix">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    <div class="uk-margin">
                        <label class="uk-form-label">
                            نام :
                        </label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" name="first_name" placeholder="نام دانشجو را وارد کنید">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">
                            نام خانوادگی :
                        </label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" name="last_name" placeholder="نام خانوادگی دانشجو را وارد کنید">
                        </div>
                    </div>
                     <div class="uk-margin">
                        <label class="uk-form-label">
                            شماره دانشجویی :
                        </label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" name="student_id" placeholder="شماره دانشجویی را وارد کنید">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">
                            شماره صندلی :
                        </label>
                        <div class="uk-form-controls">
                            <input type="text" class="uk-input" name="chair_number" placeholder="شماره صندلی را وارد کنید">
                        </div>
                    </div>

                    @if(Session::has('Error'))
                        <div class="uk-margin">
                            <div class="uk-alert uk-alert-danger">{{ Session::get('Error') }}</div>
                        </div>
                    @endif

                    <div class="uk-grid uk-grid-collapse uk-margin uk-child-width-1-2" uk-grid>
                        <button class="uk-button uk-button-primary " type="submit">افزودن</button>
                        <button class="uk-button uk-button-danger uk-modal-close " type="button">انصراف</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="get_signature" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title uk-text-right">دریافت امضا</h2>
                <form
                action="{{ route('get_signature_from_teacher') }}"
                method="post"
                class="uk-form-stacked rtl uk-clearfix">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                    <div class="uk-margin">
                        <button class="uk-button uk-button-primary uk-width-1-1">دریافت امضا از استاد درس</button>
                    </div>
                </form>

                <hr class="uk-divider-icon">

                <form
                action="{{ route('get_signature_from_other') }}"
                method="post">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                <div class="uk-margin">
                    <input type="text" name="signer_id" class="uk-input" placeholder="کد پرسنلی امضا کننده">
                    <button type="submit" class="uk-button uk-button-secondary uk-width-1-1 uk-margin">دریافت امضا</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        @if(Session::has('Error'))
            UIkit.modal('#add_new_student').show();
        @endif
    </script>
@endpush
