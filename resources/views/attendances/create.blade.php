@extends('layouts.master')

@section('title') {{ __('attendances.add_new') }} @stop

@section('css')
    <!-- استدعاء ملفات مكتبة Flatpickr للاختيار الاحترافي للوقت -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
@endsection

@section('content')
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="m-0 text-primary"><i class="fas fa-plus-circle mr-2"></i> {{ __('attendances.add_new') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- الموظف -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('attendances.employee') }}</label>
                            <select name="employee_id" class="form-control" required>
                                <option value="" disabled selected>--- {{ __('attendances.employee') }} ---</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- التاريخ -->
                        <div class="col-md-3 form-group">
                            <label>{{ __('attendances.date') }}</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-3 form-group">
                            <label>{{ __('attendances.status') }}</label>
                            <select name="status" class="form-control">
                                <option value="present">{{ __('attendances.present') }}</option>
                                <option value="late">{{ __('attendances.late') }}</option>
                                <option value="absent">{{ __('attendances.absent') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- وقت الحضور -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('attendances.check_in') }}</label>
                            <input type="text" name="check_in" class="form-control timepicker" required placeholder="08:00">
                        </div>

                        <!-- وقت الانصراف -->
                        <div class="col-md-6 form-group">
                            <label>{{ __('attendances.check_out') }}</label>
                            <input type="text" name="check_out" class="form-control timepicker" placeholder="16:00">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 px-4">حفظ البيانات</button>
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary mt-3">رجوع</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- استدعاء سكريبت مكتبة الوقت -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // جلب الإعدادات من النظام
            const officialCheckIn = "{{ $settings->official_check_in ?? '08:00' }}"; 
            const gracePeriod = parseInt("{{ $settings->grace_period_minutes ?? 15 }}"); 

            // دالة حساب وفحص التأخير
            function checkDelay(selectedTime) {
                if (!selectedTime) return;

                const [startH, startM] = selectedTime.split(':').map(Number);
                const [offH, offM] = officialCheckIn.split(':').map(Number);

                const startMinutes = (startH * 60) + startM;
                const officialMinutes = (offH * 60) + offM;

                const diff = startMinutes - officialMinutes;
                const statusSelect = document.querySelector('select[name="status"]');

                if (statusSelect) {
                    if (diff > gracePeriod) {
                        statusSelect.value = 'late';
                    } else {
                        statusSelect.value = 'present';
                    }
                }
            }

            // تفعيل مكتبة Flatpickr مع الاستماع لتغيير الوقت عبر حدث onChange الخاص بها
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // التحقق فقط إذا كان الحقل هو وقت الحضور (check_in)
                    if (instance.element.name === 'check_in') {
                        checkDelay(dateStr);
                    }
                }
            });
        });
    </script>
@endsection