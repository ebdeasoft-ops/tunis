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

  <center>  <p class="double">  قائمة العملاء   <br>
                   CUSTOMER LIST
                   </p> </center>
                   

  <br>



 


 
   <div>

                        <br>

                        <div>
                            <table dir=ltr  style=" border: 1px solid black;">
                                <thead>
                                                                
                                <tr>





                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.current balance')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.debit')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit')}} </th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.credit_oping')}}</th>
                                    <th style="font-size: 18px;font-weight: bold;" class="border-bottom-0">{{__('home.depit_oping')}} </th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.Location')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.phone')}}</th>
                                    <th class="wd-15p border-bottom-0"> {{__('home.clietName')}}</th>
                                    <th class="wd-10p border-bottom-0">#</th>


                                </tr>
                            </thead>

                            <tbody>

                                <?php $i = 0 ?>
                                @foreach (App\Models\financial_accounts::where('orginal_type',1)->get() as $user)
                                <?php $i++ ;
                                $customer=App\Models\customers::find($user->orginal_id);
                                
                                ?>

                                <tr>
                                    @if($user->debtor_current-$user->creditor_current ==0)
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.Balanced')}}</td>
                                    @elseif($user->debtor_current-$user->creditor_current >0)
                                   <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.credit')}} ( {{$user->debtor_current-$user->creditor_current}} ) {{__('home.SAR')}}</td>
                                    @else
                                    <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{__('home.debit')}} ( {{($user->debtor_current-$user->creditor_current)*-1}} ) {{__('home.SAR')}}</td>
                                     @endif
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_current }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_current }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->creditor_opening }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{$user->debtor_opening }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->address??'' }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $customer->phone??'' }}</td>
                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $user->name}}</td>



                                     <td style="font-size: 15px;font-weight: bold;" data-target="numberofpice">{{ $i}}</td>



                                </tr>


                             
                                                        @endforeach
                                </tbody>
                            </table>


                    

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
 
</body>

</html>