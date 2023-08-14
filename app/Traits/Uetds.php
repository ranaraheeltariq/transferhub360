<?php
namespace App\Traits;

use Illuminate\Http\Response;
use SoapClient;

trait Uetds
{
    // public $SOAP_URL = "https://servis.turkiye.gov.tr/services/g2g/kdgm/uetdsarizi?wsdl";
    // public $USERNAME = "872431";
    // public $PASSWORD = "I044B8IQJH";
    // Testing
    // public $SOAP_URL = "https://servis.turkiye.gov.tr/services/g2g/kdgm/test/uetdsarizi?wsdl";
    // public $USERNAME = "999999";
    // public $PASSWORD = "999999testtest";


    public function seferEkle($aracPlaka, $hareketTarihi, $hareketSaati, $seferAciklama, $firmaSeferNo, $seferBitisTarihi, $seferBitisSaati,$URL,$USERNAME,$PASSWORD)
    {
        try {
            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'ariziSeferBilgileriInput' => [
                    "aracPlaka" => $aracPlaka,
                    "seferAciklama" => $seferAciklama,
                    "hareketTarihi" => $hareketTarihi,
                    "hareketSaati" => $hareketSaati,
                    "firmaSeferNo" => $firmaSeferNo,
                    "seferBitisTarihi" => $seferBitisTarihi,
                    "seferBitisSaati" => $seferBitisSaati]
            );

            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );
            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("seferEkle", array($params));
            return $soap_return->return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function seferGuncelle($guncellenecekSeferReferansNo, $aracPlaka, $hareketTarihi, $hareketSaati, $seferAciklama, $firmaSeferNo, $seferBitisTarihi, $seferBitisSaati,$URL,$USERNAME,$PASSWORD)
    {
        try {
            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD,
                    'guncellenecekSeferReferansNo' => $guncellenecekSeferReferansNo
                ],
                'ariziSeferBilgileriInput' => [
                    "aracPlaka" => $aracPlaka,
                    "hareketTarihi" => $hareketTarihi,
                    "hareketSaati" => $hareketSaati,
                    "seferAciklama" => $seferAciklama,
                    "firmaSeferNo" => $firmaSeferNo,
                    "seferBitisTarihi" => $seferBitisTarihi,
                    "seferBitisSaati" => $seferBitisSaati]
            );

            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );


            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("seferGuncelle", array($params));


            return $soap_return->return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function seferIptal($uetdsSeferReferansNo,$URL,$USERNAME,$PASSWORD)
    {

        try {

            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo,
                 'iptalAciklama' => 'Yolcu Kaynaklı Sefer İptal Edilmiştir.'
            );


            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("seferIptal", array($params));

            return $soap_return->return->sonucKodu;
        } catch (\SoapFault $e) {
            return $e->getMessage();
        }

    }

    public function seferPlakaDegistir($uetdsSeferReferansNo, $tasitPlakaNo)
    {


    }

    public function personelEkle($uetdsSeferReferansNo, $turKodu, $uyrukUlke, $tcKimlikPasaportno, $cinsiyet, $adi, $soyadi, $telefon,$URL,$USERNAME,$PASSWORD)
    {
        try {
            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo,
                'seferPersonelBilgileriInput' => [
                    "turKodu" => $turKodu,
                    "uyrukUlke" => $uyrukUlke,
                    "tcKimlikPasaportNo" => $tcKimlikPasaportno,
                    "cinsiyet" => $cinsiyet,
                    "adi" => $adi,
                    "soyadi" => $soyadi,
                    "telefon" => $telefon]
            );
            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("personelEkle", array($params));

            $sonucKodu = $soap_return->return->sonucKodu;
            return $sonucKodu;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function personelIptal($personelTCKimlikPasaportNo, $uetdsSeferReferansNo,$URL,$USERNAME,$PASSWORD)
    {

        try {

            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'personelTCKimlikPasaportNo' => $personelTCKimlikPasaportNo,
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo,
                 'iptalAciklama' => 'Şöförü değiştir.'
            );


            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("personelIptal", array($params));

            return $soap_return->return->sonucKodu;
        } catch (\SoapFault $e) {
            return $e->getMessage();
        }
    }

    public function yolcuEkle($uetdsSeferReferansNo, $grupId, $uyrukUlke, $tcKimlikPasaportNo, $adi, $soyadi, $cinsiyet,$URL,$USERNAME,$PASSWORD)
    {
        try {
            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo,
                'seferYolcuBilgileriInput' => [
                    "uyrukUlke" => $uyrukUlke,
                    "tcKimlikPasaportNo" => $tcKimlikPasaportNo,
                    "adi" => $adi,
                    "soyadi" => $soyadi,
                    "cinsiyet" => $cinsiyet,
                    "koltukNo" => '',
                    "grupId" => $grupId]
            );
            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("yolcuEkle", array($params));


            $uetdsYolcuRefNo = $soap_return->return;
            return $uetdsYolcuRefNo;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function seferGrupEkle($uetdsSeferReferansNo, $grupAdi, $grupAciklama, $baslangicUlke, $baslangicIl, $baslangicIlce, $baslangicYer, $bitisUlke, $bitisIl, $bitisIlce, $bitisYer, $grupUcret,$URL,$USERNAME,$PASSWORD)
    {
        try {
            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo,
                'seferGrupBilgileriInput' => [
                    "grupAciklama" => $grupAciklama,
                    "baslangicUlke" => $baslangicUlke,
                    "baslangicIl" => $baslangicIl,
                    "baslangicIlce" => $baslangicIlce,
                    "baslangicYer" => $baslangicYer,
                    "bitisUlke" => $bitisUlke,
                    "bitisIl" => $bitisIl,
                    "bitisIlce" => $bitisIlce,
                    "bitisYer" => $bitisYer,
                    "grupAdi" => $grupAdi,
                    "grupUcret" => $grupUcret]
            );
            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("seferGrupEkle", array($params));

            $uetdsGrupRefNo = $soap_return->return->uetdsGrupRefNo;
            return $uetdsGrupRefNo;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function yolcuIptalUetdsYolcuRefNoIle($uetdsSeferReferansNo, $uetdsYolcuReferansNo, $iptalAciklama,$URL,$USERNAME,$PASSWORD)
    {


    }

    public function seferDetayCiktisiAl($uetdsSeferReferansNo,$URL,$USERNAME,$PASSWORD)
    {
        header('Content-type: application/pdf', true, 200);
        header('Cache-Control: public');
        header('Content-Type:  application/pdf; charset=utf-8');
        header('Content-Disposition: attachment; filename="SEFER_' . $uetdsSeferReferansNo . '.pdf"');

        try {

            $soap_client = new SoapClient($URL);

            $params = array(
                'wsuser' => [
                    'kullaniciAdi' => $USERNAME,
                    'sifre' => $PASSWORD
                ],
                'uetdsSeferReferansNo' => $uetdsSeferReferansNo
            );


            $options = array(
                'login' => $USERNAME,
                'password' => $PASSWORD,
            );

            $soap_client = new SoapClient($URL, $options);
            $soap_return = $soap_client->__soapCall("seferDetayCiktisiAl", array($params));

            return $soap_return->return->sonucPdf;
        } catch (\SoapFault $e) {
            return $e->getMessage();
        }

    }
}
