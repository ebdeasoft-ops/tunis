<!DOCTYPE html>
<html dir="rtl">

<head>
  <title> Invoice </title>
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

.tx-center{
    text-align: center;
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
          <center><span class="thick" style="font-size:16px">{{Nameen}}</span> </center>
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

<center><p>  {{__('home.voucher')}}</p></center>

  <br>
  <br>

<table style="width:100%">

    <tr class="heading">
      <td>
        <table style="border:2px solid rgba(0,0,0,.3)" dir="rtl">
          	<thead>
											<tr>

                                    <th class="border-bottom-0">{{__('accountes.Theamountpaid')}}</th>
                                    <th class="border-bottom-0">{{__('accountes.Remainingamount')}}</th>
                                    <th class="border-bottom-0">{{__('home.paymentmethod')}}</th>
                                    <th class="border-bottom-0">{{__('home.date')}}</th>
                                    <th class="border-bottom-0"> {{__('home.name')}}</th>
                                    <th class="border-bottom-0">{{ __('home.decoumentNo') }}</th>

											</tr>
										</thead>
                                        <tbody>
                                        <td><center>{{$data['transaction']['paid_amount']}}</center></td>
                                        <td><center>{{$data['transaction']['Balance']}}</center></td>
                                        <?php
                                        $paymethod='';
                                        if($data['transaction']['method_pay']== "Cash"){
                                            $paymethod= __('report.cash');
                                        }elseif ($data['transaction']['method_pay'] == 'Bank_transfer') {
                                            $paymethod = __('home.Bank_transfer');
                                        }
                                        else{
                                            $paymethod=__('report.shabka');
                                        }
                                        ?>
                                        <td><center>{{$paymethod}}</center></td>
                                        <td><center>{{$data['transaction']['date']}}</center></td>
                                                                                <td><center>{{$data['transaction']['name']}}</center></td>

                                                                                    <td><center>{{$data['transaction']['id']}}</center></td>

                                        </tbody>

        </table>
      </td>

     
    </tr>


    </table>
 



 
  <br>
 
                                    <br>
                        <p>{{__('home.employeereciver')}} : </p>
                        <br>
                        <p>{{__('home.thesignature')}} : </p>
  <div style="  position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;">

    @if(Auth()->user()->branchs_id==1)

    <center> <span dir=rtl>
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