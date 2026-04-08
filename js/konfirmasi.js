function konfirmasiHapus(){

    var konfirmasi1 = confirm("Apakah Anda yakin ingin menghapus data ini?");

    if(konfirmasi1){

        var konfirmasi2 = confirm("Data akan disembunyikan (soft delete). Lanjutkan?");

        if(konfirmasi2){
            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }

}