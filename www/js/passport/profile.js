const inputSwitcher = (e,t) => {
    let index = $(".passport-page > main .profile-settings .editor input").index(this);
    let cuel = index + 1;
    let form = [
        [$('.passport-page > main .profile-settings .editor:nth-child(' + cuel + ') input'), $('.passport-page > main .profile-settings .editor:nth-child(' + cuel + ') #control')],
        ['<i class="far fa-edit"></i>', '<i class="far fa-save inputing"></i>']
    ];


    if(form[0][0].attr('disabled')){
        form[1][0].html(form[1][1]);
        form[0][0].removeAttr('disabled');
    }
    else{
        form[1][0].html(form[0][1]);
        form[0][0].attr('disabled');
    }
}
$(document).ready(function () {
    $('.passport-page > main .profile-settings .editor #control').click(inputSwitcher);
});