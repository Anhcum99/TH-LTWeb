<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SanphamController extends Controller
{
    //
    public function index(){
        $hangsx = DB::table('hangsx')->get();
        
        return view('Admin.Content.insert_product',[
            'hangsx' => $hangsx,
        ]);
    }
    public function themsp(Request $request){
        $check = DB::table('sanpham')->insertGetId([
            'tensp' => $request->tensanpham,
            'soluong' => $request->soluong,
            'dongia' => $request->giatien,
            'giakhuyenmai'=>$request->giakhuyenmai,
            'RAM' =>$request->RAM,
            'CPU' =>$request->CPU,
            'VGA' => $request->VGA,
            'manhinh' =>$request->manhinh,
            'hedieuhanh' =>$request->hedienhanh,
            'HANGSXma_nhasanxuat'=>$request->nhasanxuat
        ]);
        if($check){
            foreach ($request->hinhanh as $value) {
                $extension = $value -> getClientOriginalExtension();
                $name = Str::random(15).'.'.$extension;
                $path = 'images/product';
                $value->move($path,$name);
                 DB::table('hinh')->insert([
                'url'=>$name,
                'tenhinh'=>'sanpham',
                'SANPHAMma_sp'=>$check
                 ]);
            }
            return redirect(route('homeql'));
        }
        else
            return redirect(route('insertsp'));  
    }
    public function xoasp($id){
        DB::delete('DELETE FROM `hinh` WHERE SANPHAMma_sp = ?',[
            $id
        ]);
       DB::delete('DELETE FROM `sanpham` WHERE ma_sp = ?',[
           $id
       ]);
        return redirect(route('homeql'));
    }
    public function hienthisua($id){
        $hangsx = DB::table('hangsx')->get();
        $sanpham = DB::table('sanpham')
        ->where('ma_sp','=',$id)
        ->get();
        return view('Admin.Content.update_product',[
            'sanpham' => $sanpham,
            'hangsx' => $hangsx,
        ]);
    }
    public function sua(Request $request, $id){
        $check = DB::table('sanpham')
        ->where('ma_sp','=',$id)
        ->update([
            'tensp' => $request->tensanpham,
            'soluong' => $request->soluong,
            'dongia' => $request->giatien,
            'giakhuyenmai'=>$request->giakhuyenmai,
            'RAM' =>$request->RAM,
            'CPU' =>$request->CPU,
            'VGA' => $request->VGA,
            'manhinh' =>$request->manhinh,
            'hedieuhanh' =>$request->hedienhanh,
        ]);
        if($check){
            return redirect(route('homeql'));
        }else
        return redirect(route('suasp',$id));  
    }

}
