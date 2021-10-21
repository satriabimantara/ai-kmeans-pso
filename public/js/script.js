$(document).ready(function () {
    function removeRequired(id_htmls)
    {
        for (let index = 0; index < id_htmls.length; index++) {
            const element = id_htmls[index];
            $(element).removeAttr("required");
        }
    }
    function addRequired(id_htmls)
    {
        for (let index = 0; index < id_htmls.length; index++) {
            const element = id_htmls[index];
            $(element).attr("required","true");
        }
    }
    const id_htmls =[
        '#jumlah_partikel',
        '#jumlah_iterasi',
        '#parameter_c1',
        '#parameter_c2',
        '#jumlah_pengujian',
    ];
    $('.page_metode').on("click",function(){
        removeRequired(id_htmls);
    })
    $('.page_pengujian').on("click",function(){
        addRequired(id_htmls);
    })
    $('.typeAlgorithm').on('change',function(){
        const typeMetode = $(this).val();
        if (typeMetode=="KMeansPSO") {
            //munculkan kotak dialog
            $('#parametersPSO').toggle('slow');
            addRequired(id_htmls);
        }else{
            //hide kotak dialog
            $('#parametersPSO').toggle('slow');
            //hilangkan required
            removeRequired(id_htmls);
        }
    });
});