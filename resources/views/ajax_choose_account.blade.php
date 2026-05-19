@if(isset($finalData) && count($finalData) > 0)
    <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped table-hover text-center">
            <thead>
                <tr>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">#</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.account_number')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.acount_name')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.tybe')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.Master')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.Master_account')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.depit_oping')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit_oping')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.debit')}}</th> {{-- الحركة مدين --}}
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit')}}</th> {{-- الحركة دائن --}}
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.current balance')}}</th>
                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.status_active')}}</th>
                                    <th style="color: #FF4F1F;font-size:11px" class="border-bottom-0">{{ __('home.operations') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach($finalData as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        {{-- بيانات الحساب --}}
                        <td>{{ $item['account']->account_number }}</td>
                        <td>{{ $item['account']->name }}</td>
                        <td style="font-weight: bold;">{{App::getLocale()=='ar'?$item['account']->acounts_type->name_ar:$item['account']->acounts_type->name_en}}</td>
                        <td>{{ $item['account']->is_master ? __('home.Master_account') : __('home.scandary')  }}</td>
                        <td>{{$item['account']->parent_account_number!=NULL?App::getLocale()=='ar'?$item['account']->parent_account->name :$item['account']->parent_account->name_en:$item['account']->acounts_type->name_ar}}</td>
                        <td class="text-info">{{ number_format($item['sums']['o_d'], 2) }}</td>
                        <td class="text-info">{{ number_format($item['sums']['o_c'], 2) }}</td>
                        <td class="text-secondary">{{ number_format($item['sums']['c_d'], 2) }}</td>
                        <td class="text-secondary">{{ number_format($item['sums']['c_c'], 2) }}</td>

                        {{-- الصافي (مدين أو دائن) --}}
                        <td style="font-size: 18px;font-weight: bold;">
                            @if($item['net_debtor'] > 0)
                                <span class="badge badge-success" style="font-size: 18px;" >{{ number_format($item['net_debtor'], 2) }} (
                                {{__('home.debit')}})</span>
                            @elseif($item['net_creditor'] > 0)
                                <span class="badge badge-danger"   style="font-size: 18px;" >{{ number_format($item['net_creditor'], 2) }} ({{__('home.credit')}})</span>
                            @else
                                0.00
                            @endif
                        </td>

                     <td>
    @if($item['account']->active == 1)
        {{-- حالة النشط --}}
        <span class="label text-success d-flex align-items-center">
            <div class="dot-label bg-success ml-1"></div>
            {{ __('users.active') }}
        </span>
    @else
        {{-- حالة غير النشط --}}
        <span class="label text-danger d-flex align-items-center">
            <div class="dot-label bg-danger ml-1"></div>
            {{ app()->getLocale() == 'ar' ? 'غير نشط' : 'Inactive' }}
        </span>
    @endif
    
        <div class="custom-control custom-switch ms-3">
                                                                <input type="checkbox" class="custom-control-input update-status" 
                                                                       id="switch-{{ $item['account']->id }}" data-id="{{ $item['account']->id }}"
                                                                       {{ $item['account']->active == 1 ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="switch-{{ $item['account']->id }}">({{__('home.status_active')}})</label>
                                                            </div>
</td>

<td>
    <div class="d-flex align-items-center">
{{-- زر كشف الحساب - Ajax --}}
    <button class="btn btn-sm btn-success ml-1 view-statement" 
            data-id="{{ $item['account']->id }}" 
            title="{{ app()->getLocale() == 'ar' ? 'كشف حساب' : 'Account Statement' }}">
        <i class="las la-file-invoice"></i>
    </button>



        {{-- زر التعديل الأصلي --}}
        <a class="modal-effect btn btn-sm btn-info edit-account-btn ml-1" 
           data-effect="effect-scale" 
           data-toggle="modal" 
           href="#edit_account_modal"
           data-id="{{ $item['account']->id }}"
           data-name="{{ $item['account']->name }}"
           data-is-master="{{ $item['account']->is_master }}"
           data-parent="{{ $item['account']->parent_account_number }}"
           title="{{ __('home.edit') }}">
           <i class="las la-pen"></i>
        </a>

        {{-- زر الحذف الأصلي --}}
        <a class="btn btn-sm btn-danger delete-btn" 
           data-id="{{ $item['account']->id }}" 
           title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
           <i class="las la-trash"></i>
        </a>
    </div>
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@else
    <div class="alert alert-danger text-center">لا توجد بيانات لعرضها</div>
@endif