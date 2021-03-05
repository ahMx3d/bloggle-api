// $(function () {
//     $('#alert-message').fadeTo(5000,500).slideUp(500, function () {
//         $('#alert-message').slideUp(500);
//     })
// });

$(()=>$('#alert-message').fadeTo(5000,500).slideUp(500, ()=>(
    $(this).slideUp(500)
)));

$('#user_image').on('change',function(e){
    //get the file name
    const fileName = e.target.files[0].name;
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
});
