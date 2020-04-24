$sum_qty =  $value['qty'];
        
        $mainpallet = Mainpallet::find($value['id']);
        $mainpallet2 = Mainpallet::find($value['id']);
    
     
        $sum_item =  (($mainpallet->mp_qty_main/$mainpallet->mp_pallet_qty_main)*$value['qty']);
    
    
        $qty_pallet += $value['qty'];
    
        $pallets = DB::table('tb_pallet')
                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                        // ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                        // ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                        ->where('tpl_mp_id',$value['id'])
                        ->get();
        $item = 0;
        $temp_qty = 0;
        $temp_qty2 = $sum_item;
        foreach($pallets as $key2 => $pallet){
            if($temp_qty2 < $mainpallet->mp_qty_main){
                echo $temp_qty." != ".$temp_qty2." ----> ";
                if($temp_qty != $temp_qty2){
                    echo $sum_item." <= ".$pallet->tpl_qty." : ";
                    if($sum_item <= $pallet->tpl_qty){
                         $sum_item = $sum_item - $pallet->tpl_qty;
                        if($sum_item < 0){
                            echo $sum_item = abs($sum_item);
                            $temp_qty += ($pallet->tpl_qty - $sum_item);
                        }else{
                            echo $sum_item = $sum_item;
                        }
                        // $temp_qty += 
                    }else{
                        echo $sum_item =  abs($sum_item - $pallet->tpl_qty);
                        $temp_qty += ($pallet->tpl_qty);
                    }
                }
            }else{
                    // if($temp_qty <= $sum_item){
                    //     if($sum_item <= $pallet->tpl_qty){
                    //         $sum_item =  $pallet->tpl_qty - $sum_item;
                    //         $temp_qty =  $pallet->tpl_qty - $sum_item ;
                            
                    //     }else{
                    //         $sum_item =  $sum_item - $pallet->tpl_qty;
                    //         $temp_qty =  $sum_item -$pallet->tpl_qty;
                    //     }
                    // }else{
                    //     $sum_item = $pallet->tpl_qty;
                    // }
            }
            
               
              
            // echo $sum_item."<br/>"; //ค่าที่เหลือใน pallet 
            // echo $temp_qty."<br/>";
            // echo $item."<br/>";
            echo "<br/>"."<br/>";  
    
    
          
            if($check_lap == 0){
                 $check_lap;
                 $container = new Container;
                 $container->ctn_type        = $request->container_size;
                 $container->ctn_location    = $request->location;
                 $container->ctn_pd_id       = $request->id;
                 $container->ctn_over_kk     = $request->over_kk;
                 $container->ctn_number      = $max_container_num;
                 $container->ctn_date        = Date('Y-m-d');
                 $container->save();
             }
    
             $containerdetial = new ContainerDetial();
             $containerdetial->ctnd_ctn_id       = $container->ctn_id;
             $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
             $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
             $containerdetial->remark            = "";
             $containerdetial->save();   
    
             $pallet = Pallets::find($pallet->tpl_id);
                        
             DB::table('tb_pallet')->where('tpl_id',$pallet->tpl_id)->update([
                 'tpl_status' =>  1,
             ]);
    
    
             ++$check_lap;
        }