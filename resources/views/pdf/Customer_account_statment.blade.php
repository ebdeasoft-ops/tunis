<!DOCTYPE html>
<html dir="rtl">

<head>
  <title>Arabic Invoice </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    * {
      font-family: DejaVu Sans !important;
    }

    body {
      font-size: 12px;
      font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
      padding: 2px;
      margin: 10px;

      color: black;
    }


    body {
      color: black;
      text-align: right;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    .justify_content {
      /* justify-content:space-between; */
    }

    th,
    td {
      /* text-align: left; */
      padding: 1px;
                  text-align: center;

    }

    tr:nth-child(even) {
      background-color: #D6EEEE;
    }

    @page {
      size: a4;
      margin: 4px;
      padding: 0;
    }




    .row {
      display: block;
      padding-left: 24;
      padding-right: 24;
      page-break-before: avoid;
      page-break-after: avoid;
    }

    .column {
      display: block;
      page-break-before: avoid;
      page-break-after: avoid;
    }
  </style>
</head>

<body>
  <div class="justify_content" style="display: flex;width:100%" dir=rtl>




    
      <?php
      $logo = camplogo;
      ?>



   
    <table>


      <tr class="heading">
      <td  style="width:35%">
          <center><span class="thick" style="font-size:19px">{{Nameen}}</span> </center>
          <center><span class="tx-16 thick"> {{describtionen}} </span> </center>
          <center><span class="tx-16 thick">{{STen}} </span> </center>
          <center><span class="tx-16 thick"> {{Taxen}} </span> </center>

        </td>
       <td style="width:35%"><center>          
        <img src="{{ public_path('assets/img/brand').'/'.$logo }}"
      style="width: 150px; height: 100px;">
</center></td>  
 

        <td style="width:30%">
        <center><span style="font-size:18px">{{Namear}}</span> </center>
        <center><span class="tx-16 thick"> {{describtionar}}</span> </center>
        <center><span class="tx-16 thick">{{STar}}</span> </center>
        <center><span class="tx-16 thick">{{Taxar}}</span> </center>

        </td>

     
      </tr>

    
    </table>
  </div><!-- invoice-header -->

  <center>  <p class="double">   كشف حساب  <br>
                    account statement
                   </p> </center>
                   

  <br>


<table style="width:100%">

    <tr class="heading">
    
      <td>
        <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);">
        <thead>
         
   <?php
                        $currentdata = \Carbon\Carbon::now()->addHours(3)->format("Y-m-d H:i:s");

                        ?>
        <tr   >

                                    <td style=" border: 1px solid black;" class="tx-16">{{ $data[6]}}</td>
                                    <td style=" border: 1px solid black;" class="tx-16">{{ __('home.clietName') }}</td>


                                                                    </tr>
         <tr>
         <td style=" border: 1px solid black;" class="tx-16"> {{ $currentdata }} </td>

               <td style=" border: 1px solid black;" class="tx-16"> {{ __('home.exportTime') }}   </td>
          </tr>
     
  </thead>

</table>
      </td>

      <td>
        <table style=" border: 1px solid black;" dir="rtl">
                            <thead>
                                   <tr>
        <td style=" border: 1px solid black;" class="tx-16"> {{ $data[4]}}</td>

              <td style=" border: 1px solid black;" class="tx-16"> {{ __('report.from') }}</td>
      </tr>
                                <tr  class="row12"  >
                                <td style=" border: 1px solid black;" class="tx-16" >{{ $data[5] }}</td>
                                <td  style=" border: 1px solid black;">{{ __('report.to') }}  </td>



                                </tr>
                                
                               
                           
                            </thead>
                           
                        </table>
      </td>

    </tr>


    </table>
 



 
   <div>


                        <div>
        <table style=" border: 1px solid black;" dir="rtl">

                                            <thead>
                                                    <tr style=" border: 1px solid black;font-size: 10px;">
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">#</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('home.exportTime') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('report.date') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0"> {{ __('home.employee') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('accountes.Theamountpaid') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('home.credit') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('home.debit') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('home.current balance') }}</th>
                                                    <th style=" border: 1px solid black;font-size: 10px;" class="border-bottom-0">{{ __('home.notesClient') }}</th>
                                                </tr>

                                            </thead>
                                            <?php
                                            $i = 1;
                                            $total_credit=$data[9];
                                            $total_debit=$data[8];
                                            $end_blance=0;
                                            ?>
                                          
                                            <tbody>
                                          
                                            
                                                 <tr style=" border: 1px solid black;">

                                                    <td style=" border: 1px solid black;font-size: 10px;">{{ $i }}</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">-</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">-</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">-</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">-</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">{{ round($total_debit,2)}}</td>
                                                    <td style=" border: 1px solid black;font-size: 10px;">{{  round($total_credit,2)}}</td>
                                              @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.credit')}} <br>( {{$total_debit-$total_credit}} )</td>
                                                @else
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.debit')}}<br> ( {{($total_debit-$total_credit)*-1}} )</td>
                                                @endif
                                                    <td>{{ __('home.oping') }}</td>

                                               
                                                </tr>
                                                    @foreach ($data[0] as $invoice)
                                                      <?php
                                                      
                                                      $total_credit+=$invoice['credit']  ;
                                                      $total_debit+=$invoice['depit']  ;
                                            $i++;
                                            ?>
                                                <tr style=" border: 1px solid black;">

                                                    <td style=" border: 1px solid black;">{{ $i }}</td>
                                                    <td style=" border: 1px solid black;">{{ $invoice['date'] }}</td>
                                                    <td style=" border: 1px solid black;">{{ $invoice['date_export'] }}</td>
                                                    <td style=" border: 1px solid black;">{{ $invoice['user'] }}</td>
                                                    <td style=" border: 1px solid black;">{{ $invoice['recive_amount'] }}</td>
                                                    <td style=" border: 1px solid black;">{{ round($invoice['depit'],2) }}</td>
                                                    <td style=" border: 1px solid black;">{{ round($invoice['credit'],2) }}</td>
                                            @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.credit')}}<br> ( {{$total_debit-$total_credit}} ) </td>
                                                @else
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.debit')}} <br> ( {{($total_debit-$total_credit)*-1}} )</td>
                                                @endif              <td>{{ $invoice['note'] }}</td>

                                               
                                                </tr>
                                                  @endforeach
           <tr>

                                                    <td style=" border: 1px solid black;">-</td>
                                                    <td style=" border: 1px solid black;">-</td>
                                                    <td style=" border: 1px solid black;">-</td>
                                                    <td style=" border: 1px solid black;">-</td>
                                                    <td style=" border: 1px solid black;">-</td>
                                                    <td style=" border: 1px solid black;">{{ round($total_debit,2)}}</td>
                                                    <td style=" border: 1px solid black;">{{ round($total_credit,2)}}</td>
  @if($total_debit-$total_credit ==0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                                @elseif($total_debit-$total_credit >0)
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.credit')}}<br> ( {{$total_debit-$total_credit}} ) </td>
                                                @else
                                                <td style="font-size: 10px;font-weight: bold;border: 1px solid black;" data-target="numberofpice">{{__('home.debit')}}<br> ( {{($total_debit-$total_credit)*-1}} ) </td>
                                                @endif                                                       <td>-</td>

                                               
                                                </tr>
                                            </tbody>
                                        </table>
                                   <br>

                            <?php
                            $customer = App\Models\customers::find($data[7]);
                            ?>
                         
                            <br>

                            <div class="table-padding">

                            </div>
                            <br>
                        </div>
                            <br>
                        </div>

  <div dir="rtl">
 
  </div>
  <br>

 <center>

   </center>

  </div>
  <br>
<span style="color:black"> 

</span>

  <br>

  <div>

  </div>
  </div>
  <div style="  position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;">

    @if(Auth()->user()->branchs_id==1)

    <center> <span>
        {{addressar}}
      </span>
    </center>


    <center> <span> {{addressen}}
      </span>
    </center>

    @endif
  </div>
</body>

</html>