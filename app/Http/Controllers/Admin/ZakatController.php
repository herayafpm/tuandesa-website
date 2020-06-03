<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;
use App\Http\Requests\ZakatRequest;
use App\Models\ZakatAmil;
use App\Models\ZakatPembagian;
use App\Models\User;
use Currency;

class ZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zakats = Zakat::latest()->paginate(10);
        return view('admin.zakats.index',compact('zakats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.zakats.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ZakatRequest $request)
    {
        $name = $request->name;
        $date = explode(' - ',$request->date);
        $start = $date[0].' 00:00:00';
        $end = $date[1].' 23:59:59';
        $params = [
            'name' => $name,
            'start' => $start,
            'end' => $end
        ];
        if(Zakat::create($params)){
            flash('Zakat berhasil ditambahkan')->success();
        }else{
            flash('Zakat gagal ditambahkan')->error()->important();
        }
        return redirect()->route('zakats.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zakat = Zakat::findOrFail($id);
        return view('admin.zakats.show',compact('zakat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $zakat = Zakat::findOrFail($id);
        return view('admin.zakats.form',compact('zakat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ZakatRequest $request, $id)
    {
        $zakat = Zakat::findOrFail($id);
        $name = $request->name;
        $date = explode(' - ',$request->date);
        $start = $date[0].' 00:00:00';
        $end = $date[1].' 23:59:59';
        $params = [
            'name' => $name,
            'start' => $start,
            'end' => $end
        ];
        if($zakat->update($params)){
            flash('Zakat berhasil diubah')->success();
        }else{
            flash('Zakat gagal diubah')->error()->important();
        }
        return redirect()->route('zakats.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zakat = Zakat::findOrFail($id);
        if($zakat->delete()){
            flash('Zakat berhasil dihapus')->success();
        }else{
            flash('Zakat gagal dihapus')->error()->important();
        }
        return redirect()->route('zakats.index');
    }

    public function amil_destroy($id)
    {
        $zakatamil = ZakatAmil::findOrFail($id);
        if($zakatamil->delete()){
            flash('Zakat Amil berhasil dihapus')->success();
        }else{
            flash('Zakat Amil gagal dihapus')->error()->important();
        }
        return redirect()->back();
    }

    public function pembagian_create($id)
    {
        $zakat = Zakat::findOrFail($id);
        $users = User::role('User')->get();
        $amils = User::role('Amil')->get();
        return view('admin.zakats.form_pembagian',compact('zakat','users','amils'));
    }
    public function pembagian_store(Request $request, $id)
    {
        $presentasePenerima = (int) $request->presentase_user;
        $presentaseAmil = (int) $request->presentase_amil;
        if(($presentaseAmil + $presentasePenerima) != 100){
            flash('Total Presentase tidak sama dengan 100%')->error()->important();
            return redirect()->back();
        }
        $zakat = Zakat::findOrFail($id);
        $penerimaZakat = $request->user_id;
        $penerimaAmil = $request->amil_id;
        $penerimaFisabililah = $request->fisabililah_id;
        
        $beras = 0;
        $uang = 0;
        foreach ($zakat->amils as $amil) {
            $beras += (float) $amil->beras;
            $uang += (int) $amil->uang;
        }
        // uang
        $totalBerasPenerima = ($beras * $presentasePenerima)/100;
        $totalBerasAmil = ($beras * $presentaseAmil)/100;
        $berasPerPenerima = $totalBerasPenerima / sizeof($penerimaZakat);
        $berasPerAmil = $totalBerasAmil / sizeof($penerimaAmil);
        // uang
        $totalUangPenerima = ($uang * $presentasePenerima)/100;
        $totalUangKoordintor = ($uang * $presentaseAmil)/100;
        $uangPerPenerima = $totalUangPenerima / sizeof($penerimaZakat);
        $uangPerAmil = $totalUangKoordintor / sizeof($penerimaAmil);
        
        // fisabililah
        $sisaUangPenerima = ($uangPerPenerima - Currency::pembulatan($uangPerPenerima)) * sizeof($penerimaZakat);
        $sisaUangAmil = ($uangPerAmil - Currency::pembulatan($uangPerAmil)) * sizeof($penerimaAmil);
        $sisaUang = $sisaUangPenerima + $sisaUangAmil;

        $uangPerFisabililah = $sisaUang / sizeof($penerimaFisabililah);

        // $sisaBerasPenerima = ((float)$berasPerPenerima - floor($berasPerPenerima)) * sizeof($penerimaZakat);
        // $sisaBerasAmil = ((float)$berasPerAmil - floor($berasPerAmil)) * sizeof($penerimaZakat);
        // $sisaBeras = $sisaBerasPenerima + $sisaBerasAmil;

        // $berasPerFisabililah = $sisaBeras / sizeof($penerimaFisabililah);
        // var_dump($beras);
        // var_dump($berasPerAmil);
        // var_dump($berasPerPenerima);
        // var_dump(floor($berasPerAmil));
        // var_dump(floor($berasPerPenerima));
        // var_dump($sisaBerasPenerima);
        // var_dump($sisaBerasAmil);
        // var_dump($sisaBeras);
        // var_dump($berasPerFisabililah);
        // exit;
        $data = [
            'zakat_id' => $id
        ];
        foreach ($penerimaZakat as $p) {
            $data['user_id'] = $p;
            $data['beras'] = $berasPerPenerima;
            $data['uang'] = Currency::pembulatan($uangPerPenerima);
            $data['tipe'] = 'penerima';
            ZakatPembagian::create($data);
        }
        foreach ($penerimaAmil as $a) {
            $data['user_id'] = $a;
            $data['beras'] = $berasPerAmil;
            $data['uang'] = Currency::pembulatan($uangPerAmil);
            $data['tipe'] = 'amil';
            ZakatPembagian::create($data);
        }
        foreach ($penerimaFisabililah as $f) {
            $data['user_id'] = $f;
            $data['beras'] = 0;
            $data['uang'] = $uangPerFisabililah;
            $data['tipe'] = 'fisabililah';
            ZakatPembagian::create($data);
        }
        flash('Berhasil melakukan pembagian')->success();
        return redirect()->route('zakats.show', $id);
    }
    public function pembagian_reset($id)
    {
        $zakatpembagian = ZakatPembagian::where('zakat_id',$id);
        if($zakatpembagian->delete()){
            flash('Zakat Pembagian berhasil direset')->success();
        }else{
            flash('Zakat Pembagian gagal direset')->error()->important();
        }
        return redirect()->back();
    }
}
