const inputSwitcher1 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(1) input'), $('.passport-page > main .profile-settings .editor:nth-child(1) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
const inputSwitcher2 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(2) input'), $('.passport-page > main .profile-settings .editor:nth-child(2) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
const inputSwitcher3 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(3) input'), $('.passport-page > main .profile-settings .editor:nth-child(3) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
const inputSwitcher4 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(4) input'), $('.passport-page > main .profile-settings .editor:nth-child(4) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
const inputSwitcher5 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(5) input'), $('.passport-page > main .profile-settings .editor:nth-child(5) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
const inputSwitcher6 = () => {
    let form = [$('.passport-page > main .profile-settings .editor:nth-child(6) input'), $('.passport-page > main .profile-settings .editor:nth-child(6) #control')];


    if(form[0].attr('disabled')){
        form[1].html("<i class='far fa-save inputing'></i>");
        form[0].removeAttr('disabled');
    }
    else{
        form[1].html("<i class='far fa-edit'></i>");
        form[0].attr('disabled','');
    }
}
$(document).ready(function () {
    $('.passport-page > main .profile-settings .editor:nth-child(1) #control').click(inputSwitcher1);
    $('.passport-page > main .profile-settings .editor:nth-child(2) #control').click(inputSwitcher2);
    $('.passport-page > main .profile-settings .editor:nth-child(3) #control').click(inputSwitcher3);
    $('.passport-page > main .profile-settings .editor:nth-child(4) #control').click(inputSwitcher4);
    $('.passport-page > main .profile-settings .editor:nth-child(5) #control').click(inputSwitcher5);
    $('.passport-page > main .profile-settings .editor:nth-child(6) #control').click(inputSwitcher6);
});