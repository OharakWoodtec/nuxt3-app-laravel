<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//  追記
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF_FONTS;
use Carbon\Carbon;

class CreatePdfController extends Controller
{
    //
    public function createPdf(Request $request) {

        // FPDIインスタンス生成
	    $pdf = new Fpdi();
        // フォントの設定
        //$pdf->SetFont('kozminproregular');
        $font = new TCPDF_FONTS();
        $msgothic = $font->addTTFfont( resource_path('pdf/msgothic.ttf') );
        $pdf->SetFont($msgothic , '', 13,'',true);
        // テンプレートとなるPDFファイルを指定（ファイルまでのパスを引数に渡す）
        $pdf->setSourceFile(resource_path('pdf/header.pdf'));
        // 新規ページをセット
        $pdf->addPage();
        // テンプレートPDFの1ページ目を読み込み
        $page = $pdf->importPage(1); 
        // 読み込んだページをテンプレートに使用
        $pdf->useTemplate($page); 
        //日付
        $date = Carbon::now()->toDateString();
        $pdf->Text(155, 18.5, $date);
        //合計金額（税抜）
        $pdf->Text(80, 45, '999,999-');
        //
        //サイズ表
        $datas = array();
        $datas[] = array('項目名'=>'間口１', 'サイズ'=>$request->input('間口１'));
        $datas[] = array('項目名'=>'間口２', 'サイズ'=>$request->input('間口２'));
        $datas[] = array('項目名'=>'奥行１', 'サイズ'=>$request->input('奥行１'));
        $pdf->SetXY(16,55);
        $pdf->writeHTML(view("table",compact('datas'))->render(),$ln=true, $fill=0, $reseth=false, $cell=true, $align="L" );

        // JpegファイルをPdfに追加。pngファイルでは動かない
        //$pdf->Image(resource_path('sample.jpg'),  17, 160, 130.0);
        //  受信した画像データをデコードする
        $imgdata = $this->decodeImage($request->input('image'));
        $pdf->Image('@'.$imgdata, 17, 100,130,0);
        //$pdf->Rect(17, 148, 130,103,'D');

        //  footer追加
        $pdf->setSourceFile(resource_path('pdf/footer.pdf'));
		$page = $pdf->importPage(1);
		$pdf->useTemplate($page);

        // PDFファイル出力        
        //return $pdf->Output(resource_path("output.pdf"), "F");
        return $pdf->Output("output.pdf", "S");

    }

    //
    //  Base64エンコードされたimageファイルをデコードする
    //
    public function decodeImage(string $decode64Image){
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i','',$decode64Image));
    }

}
