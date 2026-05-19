
          @if (@isset($data) && !@empty($data) && count($data) >0 )
          @php
           $i=1;   
          @endphp

          <br>
          <table class="table text-md-nowrap text-center  our-table"
                                             id="example2" width="100%"
                                             style="border: 2px solid rgba(0,0,0,.3);"
                                             >
                                                <col style="width:5%">
                                                <col style="width:15%">
                                                <col style="width:20%">
                                                <col style="width:10%">
                                                <col style="width:10%">
                                                <col style="width:10%">
                                                <col style="width:15%">
                                        
            
                                                <thead>
                                                    <tr>
                                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>
                                                    <th class="border-bottom-0">{{ __('report.date') }}</th>
                                                    <th class="border-bottom-0"> {{ __('home.employee') }}</th>
                                                    <th class="border-bottom-0"> {{ __('home.acount_name') }}</th>
                                                    <th class="border-bottom-0">{{ __('accountes.Theamountpaid') }} </th>
                                                    <th class="border-bottom-0">{{ __('home.paymentmethod') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.credit') }}</th>
                                                    <th class="border-bottom-0">{{ __('home.debit') }}</th>
                                                                                                        <th class="border-bottom-0">{{ __('home.operations') }}</th>

  </tr>
            
                                                    </tr>
                                                </thead>
                                                <tbody class="">
                                                    <?php $i = 0;
                                                    ?>
            
                                                    @foreach ($data as $invoice)
                                                    <td>{{ $invoice->Opening_entry}}</td>

<td>{{ $invoice->created_at }}</td>
<td>{{ $invoice->user->name }}</td>
<td>{{ $invoice->financial_accounts_data->name }}</td>
<td>{{ $invoice->recive_amount }}</td>
<td>
    @if ($invoice->Pay_Method_Name == 'Cash')
    {{ __('report.cash') }}
    @elseif ($invoice->Pay_Method_Name == 'Bank_transfer')
    {{ __('home.Bank_transfer') }}
    @else
    {{ __('report.shabka') }}
    @endif
    <td>{{ $invoice->debtor }}</td>

    <td>{{ $invoice->creditor }}</td>
<td>    <form action="{{ '/' . ($page = 'print_Opening_entry') }}" method="POST" role="search" autocomplete="off">
                                    {{ csrf_field() }}



                                    <div class='col ' >
                                   
                                        <input type="number" class="form-control " name="record_id_print" id="record_id_print" value="{{$invoice->Opening_entry}}" title=" رقم الفاتورة " readonly required hidden>

                                        <button style="background-color: #419BB2;font-size:15px;width: 120px!important;height:30px" type="submit" id='print_record' class="btn btn-success p-1 px-2 fw-bolder">
                                            {{ __('home.print') }}
                                            <svg style="width: 15px !important" class="svg-icon-buttons" viewBox="0 0 20 20">
                                                <path d="M17.453,12.691V7.723 M17.453,12.691V7.723 M1.719,12.691V7.723 M18.281,12.691V7.723 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M16.625,6.066h-1.449V3.168c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.187-0.414,0.414v2.898H3.375c-0.913,0-1.656,0.743-1.656,1.656v4.969c0,0.913,0.743,1.656,1.656,1.656h1.449v2.484c0,0.228,0.187,0.414,0.414,0.414h9.523c0.229,0,0.414-0.187,0.414-0.414v-2.484h1.449c0.912,0,1.656-0.743,1.656-1.656V7.723C18.281,6.81,17.537,6.066,16.625,6.066 M5.652,3.582h8.695v2.484H5.652V3.582zM14.348,16.418H5.652v-4.969h8.695V16.418z M17.453,12.691c0,0.458-0.371,0.828-0.828,0.828h-1.449v-2.484c0-0.228-0.186-0.414-0.414-0.414H5.238c-0.228,0-0.414,0.186-0.414,0.414v2.484H3.375c-0.458,0-0.828-0.37-0.828-0.828V7.723c0-0.458,0.371-0.828,0.828-0.828h13.25c0.457,0,0.828,0.371,0.828,0.828V12.691z M7.309,13.312h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,13.312,7.309,13.312M7.309,15.383h5.383c0.229,0,0.414-0.187,0.414-0.414s-0.186-0.414-0.414-0.414H7.309c-0.228,0-0.414,0.187-0.414,0.414S7.081,15.383,7.309,15.383 M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484 M12.691,12.484H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,12.484,12.691,12.484M12.691,14.555H7.309c-0.228,0-0.414,0.187-0.414,0.414s0.187,0.414,0.414,0.414h5.383c0.229,0,0.414-0.187,0.414-0.414S12.92,14.555,12.691,14.555"></path>
                                            </svg>
                                        </button>






                                    </div>


                                </form></td>
</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
      <br>
      <div class="justify-content-center" id="ajax_pagination_in_search">
        <center>
        &nbsp;   &nbsp;   &nbsp;  {{ $data->links() }}  &nbsp;   &nbsp;   &nbsp; 


</center>
        </div>



         
       
           @else
           <div class="alert alert-danger">
           {{__('home.notfounddata')}}             </div>
                 @endif
