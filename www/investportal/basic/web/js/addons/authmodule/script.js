let handlers = [FormInit,ScreenInit];

for (let index = 0; index < handlers.length; index++) {
    const h = handlers[index];

    $(document).ready(h);
    
}
