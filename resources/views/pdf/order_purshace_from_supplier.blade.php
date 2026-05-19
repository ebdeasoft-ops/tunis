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
          <center><span class="thick" style="font-size:13px">{{Nameen}}</span> </center>
          <center><span class="tx-16 thick"> {{describtionen}} </span> </center>
          <center><span class="tx-16 thick">{{STen}} </span> </center>
          <center><span class="tx-16 thick"> {{Taxen}} </span> </center>

        </td>
      <td style="width:35%"><center>          
        <img src="{{ public_path('assets/img/brand').'/'.$logo }}"
      style="width: 150px; height: 100px;">
</center></td>   

        <td style="width:30%">
        <center><span style="font-size:14px">{{Namear}}</span> </center>
        <center><span class="tx-16 thick"> {{describtionar}}</span> </center>
        <center><span class="tx-16 thick">{{STar}}</span> </center>
        <center><span class="tx-16 thick">{{Taxar}}</span> </center>

        </td>

     
      </tr>

    
    </table>
  </div><!-- invoice-header -->

            <center> <p  >  {{__('home.Purchase_order_of_resources')}}</p></center>

  <br>
  <br>

                 
                        
                        
<table style="width:100%">

    <tr class="heading">
    
      <td>
        <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);">
        <thead>
         <tr>

               <td style=" border: 1px solid black;" class="tx-16"> {{$data['productsdata'][0]->supllier->id}} </td>
                        <td style=" border: 1px solid black;" class="tx-16">{{__('home.Invoice_no')}} </td>

          </tr>
          
          
      
    
  </thead>
          <body>
                                      

                                            <tr>
                                                                                                <td><span><?php echo date("Y-m-d h:i") ?></span></td>

                                                <td><span> {{ __('home.date') }}</span></td>
                                            </tr>
                                            <tr>
                                                                                                <td><span>{{$data['supllierdata']->name}}</span></td>

                                                <td><span>{{ __('home.suppliername') }}   </span></td>
                                            </tr>
                                            <tr>
                                                                                                <td><span>{{$data['supllierdata']->location}}</span></td>

                                                <td><span>{{ __('supprocesses.Location') }}</span></td>
                                            </tr>

                                            <tr>
                                                                                                <td><span>{{$data['supllierdata']->phone}}</span></td>

                                                <td><span>{{ __('supprocesses.phone') }}  </span></td>
                                            </tr>
                                          
        
                                        </body>
        
</table>






      </td>

    
    </tr>


    </table>
 



 
  <br>
  <br>
  <div dir="rtl">
    <table style=" border: 1px solid black;">
    <tbody>
        <tr style=" border: 1px solid black;">
                                        <th class="tx-center"> {{__('home.total')}}    </th>
                                        
                                                                                <th class="tx-center"> {{__('home.addedValue')}}    </th>

                                                                                <th class="tx-center"> {{__('home.price')}} </th>

                                                                                <th class="tx-center"> {{__('home.quantity')}} </th>

                                                                               <th class="wd-40p">{{ __('home.product') }}</th>
                                                                               <th class="wd-40p"> {{ __('home.productNo') }}</th>
             <th class="wd-20p">#</th>

                                       
        </tr>
        <?php $i = 0;
                                $totalprice=0;
                                $totalAddedvalue=0; ?>

                                @foreach ($data['productsdata'] as $product)
                                <?php $i++;
                                $totalprice+=$product->purchasingـprice  *$product->numberofpice;
                                $totalAddedvalue+=$product->Added_Value*$product->numberofpice;
                                ?>
                                @if($product->numberofpice!=0)

                                    <tr>

                                        <td class="tx-center">{{ ($product->Added_Value*$product->numberofpice)+($product->purchasingـprice  *$product->numberofpice)}}</td>
                                                                              
                                                                                                                      <td class="tx-center">{{ $product->Added_Value}}</td>

                                                                                                                      <td class="tx-center">{{ $product->purchasingـprice}}</td>

                                                                              
                                                                               <td class="tx-center">{{ $product->numberofpice}}</td>

                                                                               <td class="tx-12">{{$product->product_name}}</td>

                                                                               <td dir=ltr>{{$product->productData->Product_Code}}</td>

                                                                           <td>{{ $i }}</td>

                                    </tr>
                                    @endif
                                    @endforeach

                              
                                   
      



      </tbody>
    </table>
  </div>
  <br>
  <br>
  <div class='row' dir=rtl>
  
    <center>
      <table dir="rtl" style="border:2px solid rgba(0,0,0,.3);width:100%">


        <tbody>
          <tr>

            <td style=" border: 1px solid black;" class="tx-18"> <center>{{$totalprice}} </center> </td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> {{ __('home.the amount') }} </center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18"> <center> {{$totalAddedvalue}}</center></td>
            <td style=" border: 1px solid black;" class="tx-18" >  <center> {{ __('home.addedValue') }}</center> </td>

          </tr>
          <tr>
            <td style=" border: 1px solid black;" class="tx-18">  <center>{{$totalAddedvalue+ $totalprice}}</center></td>
            <td style=" border: 1px solid black;" class="tx-18">  <center> {{ __('home.total') }}  </center></td>

          </tr>
     


         
        
        </tbody>


      </table>
    </center>
  </div>
  <br>
   <br>
                        
                        <p>{{__('home.signtyre_purchase_manger')}}</p>
                        <p>{{__('home.thesignature')}} : </p>



  </div>
  <br>
<span style="color:black"> 

</span>
  <br>

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