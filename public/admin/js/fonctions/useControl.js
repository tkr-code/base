$(document).ready(function(){
   function UseContolTel(value, limite = 9) {
        let tel = value.replace(/\D/g, "");
        if (tel.length < limite || tel.length > limite) {
            return true;
        } else {
            return false;
        }
    }
})