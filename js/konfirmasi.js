function konfirmasiHapus(){

    var konfirmasi1 = confirm("Apakah Anda yakin ingin menghapus data ini?");

    if(konfirmasi1){

        var konfirmasi2 = confirm("Data yang dihapus tidak bisa dikembalikan. Lanjutkan?");

        if(konfirmasi2){
            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }

}