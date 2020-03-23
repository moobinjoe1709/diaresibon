<?php


function thai_date_fullmonth($time){   // 19 ธันวาคม 2556
    $thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");   
    $thai_month_arr=array(   
        "0"=>"",   
        "1"=>"มกราคม",   
        "2"=>"กุมภาพันธ์",   
        "3"=>"มีนาคม",   
        "4"=>"เมษายน",   
        "5"=>"พฤษภาคม",   
        "6"=>"มิถุนายน",    
        "7"=>"กรกฎาคม",   
        "8"=>"สิงหาคม",   
        "9"=>"กันยายน",   
        "10"=>"ตุลาคม",   
        "11"=>"พฤศจิกายน",   
        "12"=>"ธันวาคม"                    
    );   


       
    $thai_date_return = date("j",strtotime($time));   
    $thai_date_return.=" ".$thai_month_arr[date("n",strtotime($time))];   
    $thai_date_return.= " ".(date("Y",strtotime($time))+543);   
    $thai_date_return.= "  ".date("H:i:s",strtotime($time));
    return $thai_date_return;   
} 

function thai_date_fullmonth2($time){   // 19 ธันวาคม 2556
    $thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");   
    $thai_month_arr=array(   
        "0"=>"",   
        "1"=>"มกราคม",   
        "2"=>"กุมภาพันธ์",   
        "3"=>"มีนาคม",   
        "4"=>"เมษายน",   
        "5"=>"พฤษภาคม",   
        "6"=>"มิถุนายน",    
        "7"=>"กรกฎาคม",   
        "8"=>"สิงหาคม",   
        "9"=>"กันยายน",   
        "10"=>"ตุลาคม",   
        "11"=>"พฤศจิกายน",   
        "12"=>"ธันวาคม"                    
    );   


       
    $thai_date_return = date("j",strtotime($time));   
    $thai_date_return.=" ".$thai_month_arr[date("n",strtotime($time))];   
    $thai_date_return.= " ".(date("Y",strtotime($time))+543);   
    return $thai_date_return;   
} 


if (!function_exists('insertSingleImage')) {
    function insertSingleImage($name, $path){
        $fileName = "fileName" . rand() . time() . '.' . $name->getClientOriginalExtension();
        $name->storeAs('' . $path . '', $fileName);
        return $fileName;
//        ->with('success','You have successfully upload image.');
    }
}

if (!function_exists('insertMultipleImage')) {
    function insertMultipleImage($request, $path, $name)
    {
        $images[] = [];
        $fileName = $request->file('' . $name . '');
        foreach ($fileName as $file) {
            $name = rand() . time() . '.' . $file->getClientOriginalExtension();
            $name2 = $file->getClientOriginalName();
            $size =  $file->getClientSize();
            $type = $file->getClientOriginalExtension();
            $file->storeAs('' . $path . '', $name);
            $images['name'][] = $name;
            $images['name2'][] = $name2;
            $images['size'][] = $size;
            $images['type'][] = $type;
        }
        return $images;
//        ->with('success','You have successfully upload image.');
    }
}